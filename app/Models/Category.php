<?php

namespace App\Models;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'description'
    ];
    protected $hidden = [
        'pivot'
    ];

    protected $dates = ['deleted_at'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class); // Pertenece a muchos productos
    }
}
