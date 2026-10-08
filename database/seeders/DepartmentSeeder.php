<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'CARDIOLOGY',
            'NEUROLOGY',
            'GASTROENTEROLOGY',
            'PULMONOLOGY',
            'ORTHOPEDICS',
            'DERMATOLOGY',
        ];

        foreach ($departments as $department) {

            Department::updateOrCreate(
                [
                    'name' => $department
                ],
                [
                    'description' => null
                ]
            );
        }
    }
}
