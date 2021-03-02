<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $vendedores = Seller::has('products')->get();
        return $this->showAll($vendedores);
    }

    /**
     * Display the specified resource.
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function show(Seller $seller): JsonResponse
    {
        return $this->showOne($seller);
    }
}
