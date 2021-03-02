<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Category $category
     * @return array
     */
    public function transform(Category $category): array
    {
        return [
            'identificador' => (int) $category->id,
            'titulo' => (string) $category->name,
            'detalles' => (string) $category->description,
            'fechaCreacion' => (string) $category->created_at,
            'fechaActualizacion' => (string) $category->updated_at,
            'fechaEliminación' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' => [
                'rel' => 'self',
                'href' => route('categories.show', $category->id)
            ],
            [
                'rel' => 'category.buyers',
                'href' => route('categories.buyers.index', $category->id)
            ],
            [
                'rel' => 'category.products',
                'href' => route('categories.products.index', $category->id)
            ],
            [
                'rel' => 'category.sellers',
                'href' => route('categories.sellers.index', $category->id)
            ],
            [
                'rel' => 'category.transactions',
                'href' => route('categories.transactions.index', $category->id)
            ]
        ];
    }

    public static function originalAttribute($index): ?string
    {
        $attributes = [
            'identificador' => 'id',
            'titulo' => 'name',
            'detalles' => 'description',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public static function transformedAttribute($index): ?string
    {
        $attributes = [
            'id' => 'identificador',
            'name' => 'titulo',
            'description' => 'detalles',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion'
        ];

        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
