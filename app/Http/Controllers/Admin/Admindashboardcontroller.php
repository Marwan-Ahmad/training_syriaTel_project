<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;

// use Illuminate\Http\Request;

class Admindashboardcontroller extends Controller
{
    public function state()
    {
        $totalAccounts = User::count();
        $totalDoctors = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->count();

        $totalReceptionists = User::whereHas('role', function ($query) {
            $query->where('name', 'receptionist');
        })->count();

        $totalPatients = User::whereHas('role', function ($query) {
            $query->where('name', 'patient');
        })->count();

        $pendingApprovalRequests = User::query()
            ->where('approval_status', User::APPROVAL_PENDING)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    'doctor',
                    'receptionist',
                ]);
            })
            ->count();
        $rejectedApprovalRequests = User::query()
            ->where('approval_status', User::APPROVAL_REJECTED)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    'doctor',
                    'receptionist',
                ]);
            })
            ->count();
        $approvedApprovalRequests = User::query()
            ->where('approval_status', User::APPROVAL_APPROVED)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    'doctor',
                    'receptionist',
                ]);
            })
            ->count();

        return response()->json([
            'total_doctors' => $totalDoctors,
            'total_receptionists' => $totalReceptionists,
            'total_patients' => $totalPatients,
            'total_Accounts' => $totalAccounts,
            'pendingApprovalRequests' => $pendingApprovalRequests,
            'rejectedApprovalRequests' => $rejectedApprovalRequests,
            'approvedApprovalRequests' => $approvedApprovalRequests,
        ]);
    }
}
