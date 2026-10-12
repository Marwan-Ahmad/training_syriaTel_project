<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
// use Illuminate\Http\Request;

class DepartmentManagementController extends Controller
{
    // public function getdepartments()
    // {
    //     $departments = Department::all();

    //     if ($departments->isEmpty()) {
    //         return response()->json([
    //             "data" => [],
    //             'message' => 'No departments found.'
    //         ], 200);
    //     }

    //     return response()->json(["departments" => $departments], 200);
    // }

    public function getDepartments()
    {
        $departments = Department::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => $departments->isEmpty() ? 'No departments found.' : 'Departments retrieved successfully.',
            'departments' => $departments
        ], 200);
    }
}
