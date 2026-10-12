<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get All Users
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = User::query()
            ->where('id', '!=', auth()->id())
            ->with([
                'role:id,name',
                'patientProfile',
                'doctorProfile.department:id,name'
            ]);
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter By Role
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter By Approval Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('approval_status')) {

            $query->where(
                'approval_status',
                $request->approval_status
            );
        }

        $users = $query
            ->orderBy('id', 'desc')
            ->get()
            ->each(function ($user) {

                if ($user->role?->name !== 'doctor') {
                    $user->unsetRelation('doctorProfile');
                }

                if ($user->role?->name !== 'patient') {
                    $user->unsetRelation('patientProfile');
                }
            });

        return response()->json($users);
    }


    /*
    |--------------------------------------------------------------------------
    | Get One User
    |--------------------------------------------------------------------------
    */

    public function show(User $user)
    {
        $user->load([
            'role:id,name',
            'patientProfile',
            'doctorProfile.department:id,name'
        ]);

        if ($user->role?->name !== 'doctor') {
            $user->unsetRelation('doctorProfile');
        }

        if ($user->role?->name !== 'patient') {
            $user->unsetRelation('patientProfile');
        }

        return response()->json([
            'user' => $user
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([

            'full_name' => [
                'sometimes',
                'required',
                'string',
                'max:150'
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:150',

                Rule::unique('users', 'email')
                    ->ignore($user->id)
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30'
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        /*
         * Do not allow admin to delete himself.
         */

        if (auth()->id() === $user->id) {

            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }
}
