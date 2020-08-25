<?php

namespace Modules\ProductSearch\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ProductSearch\Entities\Product;
use Modules\ProductSearch\Entities\Facades\OpenFoodFactsSmart;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     * This is just debug test of Api Call
     * @return Renderable
     */
    public function index()
    {
        echo '<pre>';
        dd(OpenFoodFactsSmart::findOnPage('Coca cola', 0, 10));
        $collection = OpenFoodFactsSmart::barcode('20203467');
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
    public function invoke(Request $request, string $name = ' ', int $perPage = 20)
    {
        if ($request->has('product_name')) {
            $name = $request->query('product_name') ?? ' ';
        }
        if ((int)$request->query('page') > 0) {
            $page = (int)$request->query('page');
        } else {
            $page = 1;
        }
        try {
            $collection = OpenFoodFactsSmart::findOnPage($name, $page, $perPage)->setPath('invoke?product_name='.$name);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('OpenFoodFacts Api request failed: ' . $e->getMessage(), $e);
        }
        return view('productsearch::index')
            ->with('search', $name)
            ->with('products', $collection);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'external_id' => 'bail|required|max:255',
            'image_url' => 'max:255',
            'product_name' => 'max:255',
        ]);
        $product = Product::where('external_id', $request->external_id)->first();
        if ($product !== null) {
            try {
                $product->update([
                    'product_name' => $request->product_name,
                    'categories' => $request->categories,
                    'image_url' => $request->image_url
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error while updating product: ' . $e->getCode()]);
            }
            return response()->json(['success' => 'Product successfully updated!']);
        } else {
            $product = new Product();
            $product->external_id = $request->external_id;
            $product->product_name = $request->product_name;
            $product->categories = $request->categories;
            $product->image_url = $request->image_url;
            try {
                $product->save();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error while saving product: ' . $e->getMessage()]);
            }
        }
        return response()->json(['success' => 'Product successfully created!']);
    }
}
