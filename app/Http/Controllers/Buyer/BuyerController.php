<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\JsonResponse;

class BuyerController extends ApiController
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
        $compradores = Buyer::has('transactions')->get();
        return $this->showAll($compradores);
    }

    /**
     * Display the specified resource.
     *
     * @param Buyer $buyer
     * @return JsonResponse
     */
    public function show(Buyer $buyer): JsonResponse
    {
        return $this->showOne($buyer);
    }
}
