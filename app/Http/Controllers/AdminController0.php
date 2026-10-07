<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\IncidentReportUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total reports
        $totalReports = IncidentReport::count();

        $newReports = IncidentReport::whereNull('acknowledged_at')
            ->count();

        // New reports
         $inProgressReports = IncidentReport::whereNotNull('acknowledged_at')
            ->whereNull('resolved_at')
            ->count();


        // Resolved reports
        $resolvedReports = IncidentReport::whereNotNull('resolved_at')
            ->count();

        // Reports submitted today
        $todayReports = IncidentReport::submittedToday()->count();

        // Latest 3 reports
        $recentReports = IncidentReport::orderBy('submitted_at', 'desc')
            ->limit(3)
            ->get();


        /*
        =========================================================
        MONTHLY INCIDENT REPORT STATISTICS
        =========================================================
        */

        $currentYear = now()->year;

        $monthlyReports = IncidentReport::select(
                DB::raw("CAST(strftime('%m', submitted_at) AS INTEGER) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('submitted_at', $currentYear)
            ->groupBy(DB::raw("strftime('%m', submitted_at)"))
            ->orderBy(DB::raw("strftime('%m', submitted_at)"))
            ->get();


        /*
        =========================================================
        INITIALIZE ALL 12 MONTHS
        =========================================================
        */

        $monthlyReportData = array_fill(1, 12, 0);


        /*
        =========================================================
        INSERT DATABASE COUNTS
        =========================================================
        */

        foreach ($monthlyReports as $report) {

            $monthlyReportData[$report->month] = $report->total;

        }


        /*
        =========================================================
        CONVERT TO ZERO-BASED ARRAY FOR JAVASCRIPT
        =========================================================
        */

        $monthlyReportData = array_values($monthlyReportData);


        return view('admin.dashboard', compact(
            'totalReports',
            'newReports',
            'inProgressReports',
            'resolvedReports',
            'todayReports',
            'recentReports',
            'monthlyReportData',
            'currentYear'
        ));
    }
}