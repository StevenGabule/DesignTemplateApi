<?php

namespace App\Policies;

use App\Models\Design;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignPolicy
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

    public function update(User $user, Design $design)
    {
        return $design->user_id === $user->id;
    }

    public function delete(User $user, Design $design)
    {
        return $design->user_id === $user->id;
    }

}
