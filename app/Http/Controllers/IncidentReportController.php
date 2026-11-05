<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\IncidentReportAttachment;
use App\Models\Notification;

use App\Models\IncidentReportUpdate;

use App\Services\SmsService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncidentReportController extends Controller
{
    public function index()
    {
        $reports = IncidentReport::with([
            'user',
            'incidentType',
            // 'attachments'
            'attachments:id,report_id,uploaded_by,file_name,file_path,file_type,attachment_type,created_at'
        ])
        ->latest('submitted_at')
        ->get();

        return view(
            'admin.reports',
            compact('reports')
        );
    }

    public function store(
        Request $request,
        SmsService $smsService
    ) {

        $validated = $request->validate([

            'incident_type_id' => [
                'required',
                'integer',
                'exists:incident_types,id'
            ],

            'title' => [
                'required',
                'string',
                'max:150'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'location' => [
                'required',
                'string',
                'max:255'
            ],

            'purok' => [
                'required',
                'string',
                'max:100'
            ],

            'water_level' => [
                'nullable',
                'in:LOW,MODERATE,HIGH'
            ],

            'severity' => [
                'nullable',
                'in:LOW,MEDIUM,HIGH'
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10240'
            ],
        ]);

        $user = Auth::user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'You must be logged in.'
            ], 401);
        }


        try {

            $report = DB::transaction(function () use (
                $validated,
                $request,
                $user
            ) {

                $report = IncidentReport::create([

                    'report_number' => 'TEMP-' . uniqid(),

                    'user_id' => $user->id,

                    'incident_type_id' =>
                        $validated['incident_type_id'],

                    'title' =>
                        $validated['title'],

                    'description' =>
                        $validated['description'] ?? null,

                    'location' =>
                        $validated['location'],

                    'purok' =>
                        $validated['purok'],

                    'water_level' =>
                        $validated['water_level'] ?? null,

                    'severity' =>
                        $validated['severity'] ?? null,

                    'status' =>
                        'SUBMITTED',

                    'submitted_at' =>
                        now(),
                ]);

                $reportNumber =
                    'IR-' .
                    now()->format('Y') .
                    '-' .
                    str_pad(
                        $report->id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                $report->update([
                    'report_number' => $reportNumber
                ]);

                // if ($request->hasFile('photo')) {

                //     $file =
                //         $request->file('photo');

                //     $fileName =
                //         time() . '_' .
                //         $file->getClientOriginalName();

                //     // $filePath =
                //     //     $file->store(
                //     //         'incident_reports',
                //     //         'public'
                //     //     );

                //     $fileData = file_get_contents(
                //         $file->getRealPath()
                //     );


                //     IncidentReportAttachment::create([

                //         'report_id' =>
                //             $report->id,

                //         'uploaded_by' =>
                //             $user->id,

                //         'file_name' =>
                //             $fileName,

                //         // 'file_path' =>
                //         //     $filePath,

                //         'file_path' => null,

                //         'file_type' =>
                //             $file->getClientMimeType(),

                //         'attachment_type' =>
                //             'PHOTO',

                //         // 'file_data' => $fileData,

                //         'created_at' =>
                //             now(),
                //     ]);
                    
                // }

                if ($request->hasFile('photo')) {

                    $file = $request->file('photo');

                    if (!$file->isValid()) {
                        throw new \RuntimeException(
                            'Uploaded image is not valid: ' . $file->getErrorMessage()
                        );
                    }

                    // Read the image's actual binary contents.
                    $fileData = file_get_contents($file->getRealPath());

                    if ($fileData === false || strlen($fileData) === 0) {
                        throw new \RuntimeException(
                            'Unable to read the uploaded image data.'
                        );
                    }

                    $fileName = time() . '_' .
                        $file->getClientOriginalName();

                    $attachment = IncidentReportAttachment::create([
                        'report_id' => $report->id,
                        'uploaded_by' => $user->id,
                        'file_name' => $fileName,
                        'file_path' => '',
                        'file_type' => $file->getMimeType(),
                        'attachment_type' => 'PHOTO',
                        'created_at' => now(),
                    ]);

                    $connection = DB::connection();

                    $statement = $connection->getPdo()->prepare(
                        'UPDATE incident_report_attachments
                        SET file_data = ?
                        WHERE id = ?'
                    );

                    $statement->bindValue(
                        1,
                        $fileData,
                        \PDO::PARAM_LOB
                    );

                    $statement->bindValue(
                        2,
                        $attachment->id,
                        \PDO::PARAM_INT
                    );

                    $statement->execute();

                    // Verify that SQLite actually stored the image.
                    $storedSize = DB::table('incident_report_attachments')
                        ->where('id', $attachment->id)
                        ->value(DB::raw('length(file_data)'));

                    if (!$storedSize || $storedSize <= 0) {
                        throw new \RuntimeException(
                            'Image BLOB was not successfully stored in SQLite.'
                        );
                    }
                }

                Notification::create([

                    'user_id' =>
                        $user->id,

                    'report_id' =>
                        $report->id,

                    'title' =>
                        'Report Submitted',

                    'message' =>
                        'Your incident report ' .
                        $report->report_number .
                        ' has been successfully submitted.',

                    'type' =>
                        'SUBMISSION',

                    'is_read' =>
                        false,

                    'created_at' =>
                        now(),
                ]);


                return $report;
            });

            $smsMessage =
                'Your incident report (Ref: ' .
                $report->report_number .
                ') has been successfully received. ' .
                'Status: SUBMITTED. Thank you for reporting.';


            $smsService->send(
                $report,
                $user,
                $smsMessage,
                'SUBMISSION'
            );


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Incident report submitted successfully.',

                'report_number' =>
                    $report->report_number,
            ]);


        } catch (\Exception $e) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Unable to submit incident report.',

                'error' =>
                    $e->getMessage(),

            ], 500);
        }
    }

    public function updateStatus(
        Request $request,
        $id,
        SmsService $smsService
    ) {
        $validated = $request->validate([

            'status' => [
                'required',
                'in:SUBMITTED,ACKNOWLEDGED,IN_PROGRESS,RESOLVED'
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000'
            ],

            'action_taken' => [
                'nullable',
                'string',
                'max:2000'
            ],

            'resolution_feedback' => [
                'nullable',
                'string',
                'max:2000'
            ],

        ]);

        if ($validated['status'] === 'IN_PROGRESS') {
            $request->validate([
                'message' => [
                    'required',
                    'string',
                    'max:2000'
                ],
                'action_taken' => [
                    'required',
                    'string',
                    'max:2000'
                ],
            ]);
        }

        if ($validated['status'] === 'RESOLVED') {
            $request->validate([
                'message' => [
                    'required',
                    'string',
                    'max:2000'
                ],
                'action_taken' => [
                    'required',
                    'string',
                    'max:2000'
                ],
            ]);
        }

        $admin = Auth::user();

        $report = IncidentReport::findOrFail($id);


        try {

            DB::transaction(function () use (
                $validated,
                $report,
                $admin
            ) {

                $updateData = [
                    'status' => $validated['status'],
                    'updated_at' => now(),
                ];

                if ($validated['status'] === 'ACKNOWLEDGED') {

                    $updateData['acknowledged_at'] = now();
                }

                if ($validated['status'] === 'RESOLVED') {

                    $updateData['resolved_at'] = now();
                }

                $report->update($updateData);

                if (
                    $validated['status'] === 'IN_PROGRESS' ||
                    $validated['status'] === 'RESOLVED'
                ) {

                    IncidentReportUpdate::create([

                        'report_id' => $report->id,

                        'user_id' => $admin->id,

                        'status' => $validated['status'],

                        'message' =>
                            $validated['message'] ?? null,

                        'action_taken' =>
                            $validated['action_taken'] ?? null,

                        'created_at' => now(),

                    ]);
                }
            });

            $report->load('user');

            $user = $report->user;

            $smsMessage = '';

            if ($validated['status'] === 'ACKNOWLEDGED') {

                $smsMessage =
                    'Your submitted report (Ref: ' .
                    $report->report_number .
                    ') has been acknowledged by the barangay. ' .
                    'Your report is now under review. Thank you for reporting.';
            }


            if ($validated['status'] === 'IN_PROGRESS') {

                $smsMessage =
                    'Your submitted report (Ref: ' .
                    $report->report_number .
                    ') is now in progress. ' .
                    'Update: ' .
                    $validated['message'] .
                    ' Action taken: ' .
                    $validated['action_taken'] .
                    '. Thank you for reporting to IR-SYS.';
            }


            if ($validated['status'] === 'RESOLVED') {

                $smsMessage =
                    'Your submitted report (Ref: ' .
                    $report->report_number .
                    ') has been resolved. ' .
                    'Resolution: ' .
                    $validated['message'] .
                    ' Thank you for reporting.';
            }

            if ($smsMessage !== '') {

                Notification::create([

                    'user_id' =>
                        $user->id,

                    'report_id' =>
                        $report->id,

                    'title' =>
                        'Report Status Updated',

                    'message' =>
                        $smsMessage,

                    'type' =>
                        $validated['status'],

                    'is_read' =>
                        false,

                    'created_at' =>
                        now(),

                ]);
            }

            if ($smsMessage !== '') {

                try {

                    $smsService->send(
                        $report,
                        $user,
                        $smsMessage,
                        $validated['status']
                    );

                } catch (\Throwable $e) {

                    \Illuminate\Support\Facades\Log::error(
                        'SMS notification failed after report update.',
                        [
                            'report_id' =>
                                $report->id,

                            'status' =>
                                $validated['status'],

                            'error' =>
                                $e->getMessage(),
                        ]
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Report status updated successfully.',
                'status' => $report->fresh()->status,
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update report status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function adminDetails($id)
    {
        $report = IncidentReport::with([
            'user',
            'incidentType',
            // 'attachments',
            'attachments:id,report_id,uploaded_by,file_name,file_path,file_type,attachment_type,created_at',
            'updates.user'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'report' => $report,
        ]);
    }

    public function localDetails($id)
    {
        $report = IncidentReport::with([
            'user',
            'incidentType',
            // 'attachments',
            'attachments:id,report_id,uploaded_by,file_name,file_path,file_type,attachment_type,created_at',
            'updates.user'
        ])->findOrFail($id);

        if ($report->user_id !== Auth::id()) {

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this report.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'report' => $report,
        ]);
    }

}
