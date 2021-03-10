<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the Seller.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function view(User $user, Seller $seller)
    {
       return $user->id === $seller->id;
    }
    /**
     * Determine whether the user can sale.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function sale(User $user,User $seller)
    {
        return $user->id === $seller->id;
    }
    /**
     * Determine whether the user can update a product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function editProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }
    /**
     *  Determine whether the user can delete a product
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function deleteProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }
}
