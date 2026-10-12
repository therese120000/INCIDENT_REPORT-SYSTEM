<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\IncidentReportUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /*
    =========================================================
    DASHBOARD
    =========================================================
    */

    public function dashboard()
    {
        // Default date range
        $fromDate = now()->startOfMonth()->format('Y-m-d');
        $toDate = now()->format('Y-m-d');

        $statistics = $this->getReportStatistics(
            $fromDate,
            $toDate
        );

        /*
        =========================================================
        LATEST 3 REPORTS
        Filtered using the selected dashboard date range
        =========================================================
        */

        $recentReports = IncidentReport::query()
            ->whereDate('submitted_at', '>=', $fromDate)
            ->whereDate('submitted_at', '<=', $toDate)
            ->orderBy('submitted_at', 'desc')
            ->limit(3)
            ->get();

        return view('admin.dashboard', array_merge(
            $statistics,
            [
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'recentReports' => $recentReports
            ]
        ));
    }


    /*
    =========================================================
    GET DASHBOARD STATISTICS
    =========================================================
    */

    private function getReportStatistics($fromDate, $toDate)
    {
        $baseQuery = IncidentReport::query()
            ->whereDate('submitted_at', '>=', $fromDate)
            ->whereDate('submitted_at', '<=', $toDate);



        $totalReports = (clone $baseQuery)->count();


        $newReports = (clone $baseQuery)
            ->whereNull('acknowledged_at')
            ->count();



        $inProgressReports = (clone $baseQuery)
            ->whereNotNull('acknowledged_at')
            ->whereNull('resolved_at')
            ->count();


        $resolvedReports = (clone $baseQuery)
            ->whereNotNull('resolved_at')
            ->count();

            $monthlyReports = (clone $baseQuery)
                ->selectRaw('MONTH(submitted_at) as month')
                ->selectRaw('COUNT(*) as total')
                ->groupByRaw('MONTH(submitted_at)')
                ->orderByRaw('MONTH(submitted_at)')
                ->get();


        /*
        =========================================================
        MONTHLY ARRAY
        =========================================================
        */

        $monthlyReportData = array_fill(1, 12, 0);

        foreach ($monthlyReports as $report) {

            $monthlyReportData[$report->month] =
                $report->total;
        }

        $monthlyReportData = array_values(
            $monthlyReportData
        );


        /*
        =========================================================
        REPORTS FOR TABLE / PDF
        =========================================================
        */

        // $reports = (clone $baseQuery)
        //     ->orderBy('submitted_at', 'desc')
        //     ->get();

        $reports = (clone $baseQuery)
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();



        return [
            'totalReports' => $totalReports,
            'newReports' => $newReports,
            'inProgressReports' => $inProgressReports,
            'resolvedReports' => $resolvedReports,
            'monthlyReportData' => $monthlyReportData,
            'reports' => $reports
        ];
    }


    public function dashboardData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from'
        ]);

        $statistics = $this->getReportStatistics(
            $request->from,
            $request->to
        );

        return response()->json([
            'success' => true,
            'totalReports' => $statistics['totalReports'],
            'newReports' => $statistics['newReports'],
            'inProgressReports' => $statistics['inProgressReports'],
            'resolvedReports' => $statistics['resolvedReports'],
            'monthlyReportData' => $statistics['monthlyReportData']
        ]);
    }


    public function generateReportData(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from'
        ]);


        /*
        =========================================================
        GET DASHBOARD STATISTICS
        =========================================================
        */

        $statistics = $this->getReportStatistics(
            $request->from,
            $request->to
        );


        /*
        =========================================================
        FORMAT REPORT DATA
        =========================================================
        */

        $reports = $statistics['reports']->map(function ($report) {

            /*
            =============================================
            DETERMINE STATUS
            =============================================
            */

            if ($report->resolved_at) {

                $status = 'RESOLVED';

            } elseif ($report->acknowledged_at) {

                $status = $report->status ?: 'IN_PROGRESS';

            } else {

                $status = 'SUBMITTED';
            }


            return [

                'report_number' =>
                    $report->report_number,

                'resident_name' =>
                    $report->user
                        ? $report->user->name
                        : 'Unknown User',

                'title' =>
                    $report->title,

                'description' =>
                    $report->description,

                'location' =>
                    $report->location,

                'water_level' =>
                    $report->water_level,

                'severity' =>
                    $report->severity,

                'status' =>
                    $status,

                'submitted_at' =>
                    $report->submitted_at
                        ? $report->submitted_at->format('M d, Y h:i A')
                        : ''
            ];
        });


        /*
        =========================================================
        DATE DISPLAY
        =========================================================
        */

        $fromDisplay = date(
            'F d, Y',
            strtotime($request->from)
        );

        $toDisplay = date(
            'F d, Y',
            strtotime($request->to)
        );


        /*
        =========================================================
        RETURN JSON
        =========================================================
        */

        return response()->json([

            'success' => true,

            'fromDate' =>
                $fromDisplay,

            'toDate' =>
                $toDisplay,

            'totalReports' =>
                $statistics['totalReports'],

            'newReports' =>
                $statistics['newReports'],

            'inProgressReports' =>
                $statistics['inProgressReports'],

            'resolvedReports' =>
                $statistics['resolvedReports'],

            'monthlyReportData' =>
                $statistics['monthlyReportData'],

            'reports' =>
                $reports
        ]);
    }
}