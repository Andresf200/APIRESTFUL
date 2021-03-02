<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client_credentials')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return $this->showAll($products);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return $this->showOne($product);
    }
}
