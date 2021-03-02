<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\JsonResponse;

class CategoryProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client_credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function index(Category $category): JsonResponse
    {
        $products = $category->products;
        return $this->showAll($products);
    }

}
