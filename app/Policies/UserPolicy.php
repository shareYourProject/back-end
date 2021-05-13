<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can follow a user.
     *
     * @param User $user
     * @param Project $project
     * @return Response
     */
    public function follow(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can unfollow a user.
     *
     * @param User $user
     * @param Project $project
     * @return Response
     */
    public function unfollow(User $user)
    {
        return Response::allow();
    }
}
