<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\SellList;
use App\User;
class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //dd($this);
    }
    public function show(User $user, SellList $job)
    {
        return $user->id === (int)$job->user_id;
    }
    public function update(User $user, SellList $job)
    {
        return $user->id === (int)$job->user_id;
    }
}
