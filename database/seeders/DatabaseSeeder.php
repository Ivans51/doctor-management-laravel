<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDoctor;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => Role::query()->where('name', Constants::$ADMIN)->first()->id
        ]);
        User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'role_id' => Role::query()->where('name', Constants::$DOCTOR)->first()->id
        ]);
        User::factory(8)->create();
        Patient::factory(10)->create();
        Doctor::factory(10)->create();
        PatientDoctor::factory(10)->create();
        Payment::factory(10)->create();
    }
}
