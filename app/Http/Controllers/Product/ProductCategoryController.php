<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client_credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function index(Product $product): JsonResponse
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @param Category $category
     * @return JsonResponse
     */
    public function update(Request $request, Product $product, Category $category): JsonResponse
    {
        //sync, attach, syncWithoutDetaching

        // El método sync solo nos sirve para cambiar el id no agrega mas categorías
        // El método attach solo nos sive para agregar las categorías pero no respeta la integridad de las llaves primarias ya que repite el mismo id
        // El método syncWithoutDetaching agrega la nueva categoría y respeta la integridad de la base de datos sin dejar repetir categorías que ya están.

        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Product $product, Category $category): JsonResponse
    {
        if (!$product->categories()->find($category->id)){ // verificamos si la categoría existe buscandola primero y si no existe lazamos una respuesta de error
            return $this->errorResponse('La categoria especificada no es una categoria de este producto', 404);
        }
        $product->categories()->detach([$category->id]); // detach permite eliminar la categoría especificada con su id
        return $this->showAll($product->categories);
    }
}
