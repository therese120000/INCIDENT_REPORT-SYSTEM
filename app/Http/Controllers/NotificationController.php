<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get unread notification count
    |--------------------------------------------------------------------------
    */
    public function count(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        | Admin only receives/counts new report submissions.
        */
        if (strtolower($user->role ?? '') === 'admin') {

            // $count = DB::table('notifications')
            //     ->where('user_id', $user->id)
            //     ->where('type', 'SUBMISSION')
            //     ->where('is_read', 0)
            //     ->count();

            $count = DB::table('notifications')
                ->join(
                    'incident_reports',
                    'notifications.report_id',
                    '=',
                    'incident_reports.id'
                )
                ->where('notifications.type', 'SUBMISSION')
                ->whereNull('incident_reports.acknowledged_at')
                ->count();

        }

        /*
        |--------------------------------------------------------------------------
        | LOCAL / RESIDENT
        |--------------------------------------------------------------------------
        | Count all unread notifications belonging to this user.
        */
        else {

            $count = DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('is_read', 0)
                ->count();
        }

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get notifications
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */
        if (strtolower($user->role ?? '') === 'admin') {

            // $notifications = DB::table('notifications')
            //     ->where('user_id', $user->id)
            //     ->where('type', 'SUBMISSION')
            //     ->orderByDesc('created_at')
            //     ->get();

            $notifications = DB::table('notifications')
                ->join(
                    'incident_reports',
                    'notifications.report_id',
                    '=',
                    'incident_reports.id'
                )
                ->where('notifications.type', 'SUBMISSION')
                ->whereNull('incident_reports.acknowledged_at')
                ->select('notifications.*')
                ->orderByDesc('notifications.created_at')
                ->get();

        }

        /*
        |--------------------------------------------------------------------------
        | LOCAL / RESIDENT
        |--------------------------------------------------------------------------
        */
        else {

            $notifications = DB::table('notifications')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Mark notification as read
    |--------------------------------------------------------------------------
    */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure user can only mark THEIR OWN notification as read
        |--------------------------------------------------------------------------
        */
        $query = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $user->id);

        /*
        |--------------------------------------------------------------------------
        | Admin can only interact with SUBMISSION notifications
        |--------------------------------------------------------------------------
        */
        if (strtolower($user->role ?? '') === 'admin') {
            $query->where('type', 'SUBMISSION');
        }

        $updated = $query->update([
            'is_read' => 1
        ]);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    }
}