<?php

namespace Modules\ProductSearch\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ProductSearch\Entities\Product;
use OpenFoodFacts;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        echo '<pre>';
        $collection = OpenFoodFacts::barcode('20203467');
        print_r($collection);
        return view('productsearch::index');
    }

    /**
     * Make new search by name.
     * @param Request $request
     * @param string $name
     * @param int $perPage
     * @return Renderable
     */
    public function invoke(Request $request, $name, int $perPage = 20)
    {
        if ($request->has('product_name')) {
            $name = $request->query('product_name');
            echo $name;
        }
        if ($request->has('page') and $page = (int)$request->query('page') > 0) {
            // pass pager to view
            echo "Current page: $page";
        } else {
            $page = 1;
        }
        print_r([$name, $page, $perPage]);
        try {
            $collection = OpenFoodFacts::find($name)->forPage($page, $perPage);
        } catch (\Exception $e) {
            //abort(403, $e->getMessage());
            throw new BadRequestHttpException('OpenFoodFacts Api request failed: ' . $e->getMessage(), $e);
        }

        if (!$collection->count()) {
            abort(418, 'There is no items');
        }

        //print_r($collection->all());
        $products = [];
        foreach ($collection as $item) {
            //dd($item);
            $products[] = new Product([
                'external_id' => $item['_id'],
                'product_name' => $item['product_name'] ?? 'Empty product name',
                'categories' => $item['categories'] ?? '',
                'image_url' => $item['image_url'] ?? null,
            ]);
        }
        //dd($products);
        //echo $collection->toJson();
        return view('productsearch::index')
            ->with('products', $products)
            ->with('search', $name)
            ->with('page', $page);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('productsearch::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('productsearch::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('productsearch::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
