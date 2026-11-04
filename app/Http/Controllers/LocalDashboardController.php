<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\LocalReport;
use Carbon\Carbon;

class LocalDashboardController extends Controller
{
    public function dashboardData()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Not authenticated.'
            ], 401);
        }

        if (strtoupper($user->role) !== 'LOCAL') {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }


        $userReports = LocalReport::where(
            'user_id',
            $user->id
        );


        $totalReports = (clone $userReports)->count();


        $underReviewReports = (clone $userReports)
            ->whereNull('acknowledged_at')
            ->whereNull('resolved_at')
            ->count();


        $inProgressReports = (clone $userReports)
            ->whereNull('resolved_at')
            ->whereExists(function ($query) {

                $query->selectRaw('1')
                    ->from('incident_report_updates')
                    ->whereColumn(
                        'incident_report_updates.report_id',
                        'incident_reports.id'
                    )
                    ->where(
                        'incident_report_updates.status',
                        'IN_PROGRESS'
                    );
            })
            ->count();


        $resolvedReports = (clone $userReports)
            ->whereNotNull('resolved_at')
            ->count();


        $weekStart = Carbon::now('Asia/Manila')
            ->startOfWeek(Carbon::MONDAY);

        $weekEnd = Carbon::now('Asia/Manila')
            ->endOfWeek(Carbon::SUNDAY);


        $recentReports = LocalReport::with('incidentType')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [
                $weekStart,
                $weekEnd
            ])
            ->orderByDesc('created_at')
            ->get();


        $allReports = LocalReport::with('incidentType')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();


        return response()->json([

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],

            'statistics' => [
                'total' => $totalReports,
                'under_review' => $underReviewReports,
                'in_progress' => $inProgressReports,
                'resolved' => $resolvedReports,
            ],

            'week' => [
                'start' => $weekStart->format('Y-m-d'),
                'end' => $weekEnd->format('Y-m-d'),
                'reports' => $recentReports,
            ],

            'all_reports' => $allReports,

        ]);
    }
}