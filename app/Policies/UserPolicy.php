<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**

     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user, User $model)
    {
        return true;
        //return ($user->role == 'admin') || ($user->id == $model->id);
    }

    public function show(User $user)
    {
        return $user->role == 'admin';
    }

    public function create(User $user)
    {
        return $user->role == 'admin';
    }

    public function delete(User $user, User $model)
    {
        return ($user->role == 'admin') || ($user->id == $model->id);
    }

    public function update(User $user, User $model)
    {
        return ($user->role == 'admin') || ($user->id == $model->id);
    }

    public function viewAny(User $user, User $model)
    {
        return true;
        //return ($user->role === 'admin') || ($user->id === $model->id);
    }

    /**
     * Determine whether the user can view himself.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $plan
     * @return mixed
     */
    public function view(User $user, User $searchedUser)
    {
        //
        return true;
    }
}
