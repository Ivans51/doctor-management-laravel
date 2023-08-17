<?php

namespace App\Utils;

use App\Models\Role;
use App\Models\User;
use Auth;

class Testing
{
    /**
     * @param $type
     * @return void
     */
    public function createFakeUserWithRole($type): void
    {
        $roleId = Role::query()->where('name', $type)->first()->id;

        $user = User::query()
            ->where('role_id', $roleId)
            ->first();

        Auth::login($user);
    }
}
