<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Appointment;
use App\Models\Chat;
use App\Models\Doctor;
use App\Models\DoctorMedicalSpecialty;
use App\Models\MedicalSpecialty;
use App\Models\Message;
use App\Models\Patient;
use App\Models\PatientDoctor;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Schedule;
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

        $this->createUsers();

        $userIdsPatient = $this->getUserIds(Constants::$PATIENT);
        foreach ($userIdsPatient as $item) {
            Patient::factory(1, ['user_id' => $item])->create();
        }

        $userIdsDoctor = $this->getUserIds(Constants::$DOCTOR);
        foreach ($userIdsDoctor as $item) {
            Doctor::factory(1, ['user_id' => $item])->create();
        }

        PatientDoctor::factory(10)->create();
        Schedule::factory(10)->create();
        MedicalSpecialty::factory(10)->create();
        DoctorMedicalSpecialty::factory(10)->create();
        Appointment::factory(10)->create();
        Payment::factory(10)->create();
        Chat::factory()->create([
            'user1_id' => 2,
            'user2_id' => 3,
        ]);
        Message::factory(10)->create();
    }

    /**
     * @return void
     */
    private function createUsers(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => Role::query()->where('name', Constants::$ADMIN)->first()->id
        ]);
        User::factory()->create([
            'name' => 'Patient User',
            'email' => 'patient@example.com',
            'role_id' => Role::query()->where('name', Constants::$PATIENT)->first()->id
        ]);
        User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'role_id' => Role::query()->where('name', Constants::$DOCTOR)->first()->id
        ]);
        User::factory(1, [
            'role_id' => Role::query()->where('name', Constants::$ADMIN)->first()->id
        ])->create();
        User::factory(4, [
            'role_id' => Role::query()->where('name', Constants::$PATIENT)->first()->id
        ])->create();
        User::factory(4, [
            'role_id' => Role::query()->where('name', Constants::$DOCTOR)->first()->id
        ])->create();
    }

    /**
     * @param string $type
     * @return array
     */
    private function getUserIds(string $type): array
    {
        $roleId = Role::query()
            ->where('name', $type)
            ->pluck('id')
            ->first();

        return User::query()
            ->where('role_id', $roleId)
            ->pluck('id')
            ->toArray();
    }
}
