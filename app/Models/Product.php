<?php

namespace App\Models;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $transformer = ProductTransformer::class;

    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];
    protected $hidden = [
        'pivot'
    ];
    protected $dates = ['deleted_at'];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class); //Un producto pertene a un vendedor
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class); // Tiene muchas transacciones
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class); // Pertenece a muchas categorías
    }
}
