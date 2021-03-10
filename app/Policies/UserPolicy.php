<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminActions;


    /**
     * Determine whether the user can view the user
     *
     * .
     *
     * @param  \App\Models\User  $authenticateduser
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $authenticateduser, User $user)
    {
        return $authenticateduser->id === $user->id;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Models\User  $authenticateduser
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update(User $authenticateduser, User $user)
    {
        return $authenticateduser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\User  $authenticateduser
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $authenticateduser, User $user)
    {
        return $authenticateduser->id === $user->id && $authenticateduser->token()->client
                ->personal_acces_client;
    }

}
