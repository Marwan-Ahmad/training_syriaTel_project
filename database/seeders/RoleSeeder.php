<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'patient',
            'doctor',
            'receptionist',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'name' => $role
            ]);
        }
    }
}
