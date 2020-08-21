<?php

namespace Modules\ProductSearch\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//use Modules\ProductSearch\Entities\OpenFoodFactsSmart;
use Modules\ProductSearch\Entities\Product;
use OpenFoodFacts;
use Modules\ProductSearch\Entities\Facades\OpenFoodFactsSmart;
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
    public function invoke(Request $request, $name, int $perPage = 20)
    {
        if ($request->has('product_name')) {
            $name = $request->query('product_name') ?? ' ';
        }
        if ($request->has('page') and $page = (int)$request->query('page') > 0) {
            // pass pager to view
            echo "Current page: $page";
        } else {
            $page = 1;
        }
        //print_r([$name, $page, $perPage]);
        try {
            $collection = OpenFoodFactsSmart::findOnPage($name, $page, $perPage);
            // TODO add pager to collection in array ->forPage($page, $perPage)
            //$collection['productsOnPageCollection']->forPage($page, $perPage);
        } catch (\Exception $e) {
            //abort(403, $e->getMessage());
            throw new BadRequestHttpException('OpenFoodFacts Api request failed: ' . $e->getMessage(), $e);
        }
        // keep for future api & process empty in view
        /*if (!$collection['productsOnPageCollection']->count()) {
            abort(418, 'There is no items');
        }*/
        $products = $collection['productsOnPageCollection']->all();
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

        $product = Product::where('external_id', $request->external_id)->first();

        if ($product !== null) {
            $product->update(['product_name' => $request->product_name, 'categories' => $request->categories, 'image_url' => $request->image_url]);
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
                return response()->json(['error' => 'Error while saving product: ' . $e->getCode()]);
            }
        }

        return response()->json(['success' => 'Product successfully created!']);
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
