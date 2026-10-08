<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where(
            'name',
            'admin'
        )->firstOrFail();

        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com'
            ],
            [
                'role_id' => $adminRole->id,
                'full_name' => 'ClinicCare Admin',
                'phone' => null,
                'password' => Hash::make('123456789'),
                "approval_status" => User::APPROVAL_APPROVED
            ]
        );
    }
}
