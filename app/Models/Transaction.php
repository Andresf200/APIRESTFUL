<?php

namespace App\Models;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];
    protected $dates = ['deleted_at'];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class); // Una transaccion pertene a un comprador
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // Una transaccion pertene a un producto
    }
}
