<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Mail\ApprovalStatusChangedMail;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Pending Approval Requests
    |--------------------------------------------------------------------------
    */

    public function getpendingstatus()
    {

        $users = User::query()
            ->where('approval_status', User::APPROVAL_PENDING)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    'doctor',
                    'receptionist'
                ]);
            })
            ->with([
                'role:id,name',
                'doctorProfile.department:id,name'
            ])
            ->select([
                'id',
                'role_id',
                'full_name',
                'email',
                'phone',
                'approval_status',
                // 'created_at'
            ])
            ->get()
            ->each(function ($user) {
                if ($user->role?->name === 'receptionist') {
                    $user->unsetRelation('doctorProfile');
                }
            });
        if (!$users) {
            return response()->json([
                'message' => 'No pending approval requests found.'
            ], 404);
        }

        return response()->json($users);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Single Approval Request
    |--------------------------------------------------------------------------
    */

    public function show_one_approval(User $user)
    {

        $user->load([
            'role:id,name',
            'doctorProfile.department:id,name'
        ]);
        if ($user->role?->name === 'receptionist') {
            $user->unsetRelation('doctorProfile');
        }

        if (!$this->isApprovableRole($user)) {
            return response()->json([
                'message' => 'This user does not require approval.'
            ], 422);
        }

        if ($user->approval_status !== User::APPROVAL_PENDING) {
            return response()->json([
                'message' => 'This approval request is no longer pending.'
            ], 409);
        }

        return response()->json([
            'user' => $user
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Approve / Reject Request
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'approval_status' => [
                'required',
                Rule::in([
                    User::APPROVAL_APPROVED,
                    User::APPROVAL_REJECTED,
                ])
            ],
        ]);

        $user->load('role')->each(function ($user) {
            if ($user->role?->name === 'receptionist') {
                $user->unsetRelation('doctorProfile');
            }
        });

        if (!$this->isApprovableRole($user)) {
            return response()->json([
                'message' => 'This user does not require approval.'
            ], 422);
        }

        if ($user->approval_status !== User::APPROVAL_PENDING) {
            return response()->json([
                'message' => 'This approval request has already been processed.'
            ], 409);
        }

        $user->update([
            'approval_status' => $validated['approval_status']
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

        $emailSent = true;

        try {
            if ($validated['approval_status'] === User::APPROVAL_APPROVED) {
                Mail::raw('your account has been approved you can now log in to your account best regards', function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('account approval status');
                });
            } else {
                Mail::raw('your account has been rejected please recreate your account.. for more information best regards', function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('account approval status');
                });
            }
        } catch (\Throwable $exception) {

            $emailSent = false;

            Log::error(
                'Failed to send approval status email.',
                [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $exception->getMessage()
                ]
            );
        }

        return response()->json([
            'message' => $validated['approval_status']
                === User::APPROVAL_APPROVED
                ? 'User approved successfully.'
                : 'User rejected successfully.',

            'approval_status' => $user->approval_status,

            'email_sent' => $emailSent
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Check Role
    |--------------------------------------------------------------------------
    */

    private function isApprovableRole(User $user): bool
    {
        return in_array(
            $user->role?->name,
            [
                'doctor',
                'receptionist'
            ],
            true
        );
    }
}
