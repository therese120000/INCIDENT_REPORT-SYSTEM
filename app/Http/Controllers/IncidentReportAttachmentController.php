<?php

namespace App\Http\Controllers;

use App\Models\IncidentReportAttachment;
use Illuminate\Http\Request;

class IncidentReportAttachmentController extends Controller
{
    public function show(Request $request, $id)
    {
        $attachment = IncidentReportAttachment::with('report')
            ->findOrFail($id);

        $user = $request->user();

        // Allow the report owner or an administrator.
        $isAdmin = strtoupper($user->role ?? '') === 'ADMIN';

        $isOwner = $attachment->report &&
            (int) $attachment->report->user_id === (int) $user->id;

        abort_unless($isAdmin || $isOwner, 403);

        // Only allow supported image formats.
        $allowedTypes = [
            'image/jpeg',
            'image/png',
        ];

        abort_unless(
            in_array($attachment->file_type, $allowedTypes, true),
            415
        );

        if (empty($attachment->file_data)) {
            abort(404, 'Image data not found.');
        }

        return response($attachment->file_data, 200, [
            'Content-Type' => $attachment->file_type,
            'Content-Disposition' => 'inline; filename="' .
                basename($attachment->file_name) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}