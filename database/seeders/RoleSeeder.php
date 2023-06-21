<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Utils\Constants;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create(['name' => Constants::$ADMIN]);
        Role::query()->create(['name' => Constants::$PATIENT]);
        Role::query()->create(['name' => Constants::$DOCTOR]);
    }
}
