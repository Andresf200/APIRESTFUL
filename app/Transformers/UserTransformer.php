<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identificador' => (int) $user->id,
            'nombre' => (string) $user->name,
            'correo' => (string) $user->email,
            'esVerificado'=> (int) $user->verified,
            'esAdministrador' => ($user->admin === 'true'),
            'fechaCreacion' => (string) $user->created_at,
            'fechaActualizacion' => (string) $user->updated_at,
            'fechaEliminación' => isset($user->deleted_at) ? (string) $user->deleted_at : null,
            'links' => [
                'rel' => 'self',
                'href' => route('users.show', $user->id)
            ],
        ];
    }

    public static function originalAttribute($index): ?string
    {
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'esVerificado'=> 'verified',
            'esAdministrador' => 'admin',
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
            'name' => 'nombre',
            'email' => 'correo',
            'password' => 'contraseña',
            'verified'=> 'esVerificado',
            'admin' => 'esAdministrador',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion'
        ];

        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
