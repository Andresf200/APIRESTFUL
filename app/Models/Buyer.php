<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    use HasFactory;

    public $transformer = BuyerTransformer::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuyerScope());
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class); //Un comprador tiene muchas transacciones
    }
}
