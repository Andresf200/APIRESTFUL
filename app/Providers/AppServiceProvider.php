<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength('191');

        User::created(function ($user){
             retry(5, function () use ($user){
                 Mail::to($user->email)->send(new UserCreated($user));
             }, 100);
        });

        User::updated(function ($user){
            if ($user->isDirty('email')){ // isDirty nos permite verificar que un atributo a cambiado en este caso solo necesiamos saber si cambio el correo el usuario
                 retry(5, function () use ($user){
                     Mail::to($user->email)->send(new UserMailChanged($user));
                 }, 100);
            }
        });

        Product::updated(function ($product){
            if ($product->quantity == 0 && $product->estaDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });

    }
}
