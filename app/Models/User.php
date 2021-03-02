<?php

namespace App\Models;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verified_token',
        'verification_token',
        'admin'
    ];

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    public function setNameAttribute($valor)
    {
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor): string
    {
        return ucwords($valor);
    }

    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = strtolower($valor);
    }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarVerificationToken(): string
    {
        return Str::random(40);
    }
}
