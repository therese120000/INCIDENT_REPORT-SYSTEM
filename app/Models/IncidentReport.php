<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $table = 'incident_reports';

    protected $fillable = [
        'report_number',
        'user_id',
        'incident_type_id',
        'title',
        'description',
        'location',
        'purok',
        'water_level',
        'severity',
        'status',
        'assigned_admin_id',
        'submitted_at',
        'acknowledged_at',
        'resolved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function incidentType()
    {
        return $this->belongsTo(
            IncidentType::class,
            'incident_type_id'
        );
    }

    public function attachments()
    {
        return $this->hasMany(
            IncidentReportAttachment::class,
            'report_id'
        );
    }
    

    public function updates()
    {
        return $this->hasMany(
            IncidentReportUpdate::class,
            'report_id'
        )->orderBy('created_at', 'asc');
    }

    public function notifications()
    {
        return $this->hasMany(
            Notification::class,
            'report_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeNewReports($query)
    {
        return $query
            ->where('status', 'SUBMITTED');
    }

    public function scopeResolved($query)
    {
        return $query
            ->where('status', 'RESOLVED');
    }

    public function scopeSubmittedToday($query)
    {
        return $query
            ->whereDate('submitted_at', today());
    }
}
