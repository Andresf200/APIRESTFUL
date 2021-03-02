<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function index(Transaction $transaction): JsonResponse
    {
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
    }
}