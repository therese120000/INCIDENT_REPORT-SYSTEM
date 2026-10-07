<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalReport extends Model
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
        'latitude',
        'longitude',
        'water_level',
        'severity',
        'status',
        'assigned_admin_id',
        'submitted_at',
        'acknowledged_at',
        'resolved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function incidentType()
    {
        return $this->belongsTo(
            IncidentType::class, 
            'incident_type_id');
    }
}