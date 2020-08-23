<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 08.08.2020
 * Time: 9:21
 */

namespace Modules\ProductSearch\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use OpenFoodFacts\Laravel\OpenFoodFacts;


class OpenFoodFactsSmart extends OpenFoodFacts
{
    /**
     * @param string $searchterm
     * @param int $page
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     * @throws \OpenFoodFacts\Exception\BadRequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function findOnPage(string $searchterm, int $page = 1, int $itemsPerPage = 20)
    {
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
            $pageResults = $this->api->search($searchterm, $page++, $pageSize);
            $totalMatches = $pageResults->searchCount();
            $pages = (int)ceil($totalMatches / $pageResults->getPageSize());
            $products = $products->concat(iterator_to_array($pageResults));
        } while ($page < $pages and count($products) < $itemsPerPage);
        return new LengthAwarePaginator(
            $this->processProducts($products), $totalMatches, $pageSize, $currentPage,
            [
                'search' => $searchterm
            ]);
    }

    /**
     * @param $products Collection
     * @return Collection
     */
    private function processProducts($products)
    {
        return $products->map(function ($product) {
            return new Product([
                'external_id' => $product->_id,
                'product_name' => $product->product_name ?? null,
                'categories' => $product->categories ?? '',
                'image_url' => $product->image_url ?? null,
            ]);
        });
    }
}