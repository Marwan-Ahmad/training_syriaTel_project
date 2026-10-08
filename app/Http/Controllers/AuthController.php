<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\PatientProfile;
use App\Models\DoctorProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Patient Register
    |--------------------------------------------------------------------------
    */

    public function registerPatient(Request $request)
    {
        $validated = $request->validate(
            [
                'full_name' => [
                    'required',
                    'string',
                    'max:150'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    'unique:users,email'
                ],

                'phone' => [
                    'nullable',
                    // "phone",
                    'string',
                    'between:10,10'
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed'
                ],

                'date_of_birth' => [
                    'required',
                    'date',
                    // 'date_format:Y-m-d',
                    'before:today'
                ],

                'sex' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'address' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                "between" => "The :attribute must be exactly 10 digits.",
            ]
        );

        $user = DB::transaction(function () use ($validated) {

            $role = Role::where('name', 'patient')->firstOrFail();

            $user = User::create([
                'role_id' => $role->id,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'approval_status' => User::APPROVAL_APPROVED,
            ]);

            PatientProfile::create([
                'user_id' => $user->id,
                'date_of_birth' => $validated['date_of_birth'],
                'sex' => $validated['sex'],
                'address' => $validated['address'] ?? null,
            ]);

            return $user;
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load([
            'role',
            'patientProfile'
        ]);

        return response()->json([
            'message' => 'Patient registered successfully.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Doctor Register
    |--------------------------------------------------------------------------
    */

    public function registerDoctor(Request $request)
    {
        $validated = $request->validate(
            [
                'full_name' => [
                    'required',
                    'string',
                    'max:150'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    'unique:users,email'
                ],

                'phone' => [
                    'nullable',
                    'string',

                    'between:10,10'
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed'
                ],

                'specialization' => [
                    'required',
                    'string',
                    'max:150'
                ],

                'department_id' => [
                    'required',
                    'integer',
                    'exists:departments,id'
                ],
            ],
            [
                "between" => "The :attribute must be exactly 10 digits.",
            ]
        );

        $user = DB::transaction(function () use ($validated) {

            $role = Role::where('name', 'doctor')->firstOrFail();

            $user = User::create([
                'role_id' => $role->id,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'approval_status' => User::APPROVAL_PENDING,
            ]);

            DoctorProfile::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'specialization' => $validated['specialization'],
            ]);

            return $user;
        });

        // $token = $user->createToken('auth_token')->plainTextToken;

        // $user->load([
        //     'role',
        //     'doctorProfile.department'
        // ]);

        // return response()->json([
        //     'message' => 'Doctor registered successfully.',
        //     'user' => $user,
        //     'token' => $token,
        // ], 201);
        return response()->json([
            'message' => 'Doctor registered successfully and is waiting for admin approval.',
            'approval_status' => $user->approval_status,
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Receptionist Register
    |--------------------------------------------------------------------------
    */

    public function registerReceptionist(Request $request)
    {
        $validated = $request->validate(
            [
                'full_name' => [
                    'required',
                    'string',
                    'max:150'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    'unique:users,email'
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'between:10,10'
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed'
                ],
            ],
            [
                "between" => "The :attribute must be exactly 10 digits.",
            ]
        );

        $role = Role::where('name', 'receptionist')->firstOrFail();

        $user = User::create([
            'role_id' => $role->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'approval_status' => User::APPROVAL_PENDING,
        ]);

        // $token = $user->createToken('auth_token')->plainTextToken;

        // $user->load('role');

        // return response()->json([
        //     'message' => 'Receptionist registered successfully.',
        //     'user' => $user,
        //     'token' => $token,
        // ], 201);

        return response()->json([
            'message' => 'Receptionist registered successfully and is waiting for admin approval.',
            'approval_status' => $user->approval_status,
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    // public function login(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => [
    //             'required',
    //             'email'
    //         ],

    //         'password' => [
    //             'required',
    //             'string'
    //         ],
    //     ]);

    //     $user = User::where('email', $validated['email'])
    //         ->first();

    //     if (
    //         !$user ||
    //         !Hash::check($validated['password'], $user->password)
    //     ) {

    //         return response()->json([
    //             'message' => 'Invalid email or password.'
    //         ], 401);
    //     }

    //     $token = $user
    //         ->createToken('auth_token')
    //         ->plainTextToken;

    //     $user->load([
    //         'role',
    //         'patientProfile',
    //         'doctorProfile.department'
    //     ]);

    //     return response()->json([
    //         'message' => 'Login successful.',
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        if ($user->approval_status === User::APPROVAL_PENDING) {
            return response()->json([
                'message' => 'Your account is waiting for administrator approval.'
            ], 403);
        }

        if ($user->approval_status === User::APPROVAL_REJECTED) {
            return response()->json([
                'message' => 'Your account has been rejected.'
            ], 403);
        }

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        $user->load([
            'role',
            'patientProfile',
            'doctorProfile.department'
        ]);

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function me(Request $request)
    {
        $user = $request->user();

        $user->load([
            'role',
            'patientProfile',
            'doctorProfile.department'
        ]);

        return response()->json([
            'user' => $user
        ]);
    }
}
