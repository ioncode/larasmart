<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 08.08.2020
 * Time: 9:21
 */

namespace Modules\ProductSearch\Entities;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use OpenFoodFacts\Laravel\OpenFoodFacts;


class OpenFoodFactsSmart extends OpenFoodFacts
{
    public function findOnPage(string $searchterm, int $page = 0, int $itemsPerPage = 20)
    {
        $debug=[];
        $currentPage = $page;
        if (empty($searchterm)) {
            throw new InvalidArgumentException("Specify a search term to find data for matching products");
        }

        $products = Collection::make();

        if ($itemsPerPage > 100) {
            $pageSize = 100;
        } else {
            $pageSize = $itemsPerPage;
        }

        do {
            $pageResults = $this->api->search($searchterm, ++$page, $pageSize);
            $totalMatches = $pageResults->searchCount();

            $pages = (int)ceil($totalMatches / $pageResults->getPageSize());

            $products = $products->concat(iterator_to_array($pageResults));

        } while ($page < $pages and count($products) < $itemsPerPage);

        if (env('APP_DEBUG')){
            $debug=[
                'totalMatches' => $totalMatches,
                'totalPages' => $pages,
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'search' => $searchterm,
            ];
        }
        return array_merge([
            'productsOnPageCollection' => $this->processProducts($products),
        ], $debug);
    }

    /**
     * @param $products Collection
     * @return Collection
     */
    private function processProducts($products)
    {
        return $products->map(function ($product) {
            //reset($product);
            return new Product([
                'external_id' => $product->_id,
                'product_name' => $product->product_name ?? 'Empty product name',
                'categories' => $product->categories ?? '',
                'image_url' => $product->image_url ?? null,
            ]);
        });
    }
}