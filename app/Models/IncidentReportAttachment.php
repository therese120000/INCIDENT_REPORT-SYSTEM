<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReportAttachment extends Model
{
    protected $table = 'incident_report_attachments';

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'attachment_type',
        'file_data',
        'created_at',
    ];

    protected $hidden = [
        'file_data',
    ];

    public function report()
    {
        return $this->belongsTo(
            IncidentReport::class,
            'report_id'
        );
    }

    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}
