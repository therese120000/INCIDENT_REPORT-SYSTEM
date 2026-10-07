<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\IncidentReport;

class IncidentReportUpdate extends Model
{
    protected $table = 'incident_report_updates';

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'user_id',
        'status',
        'message',
        'action_taken',
        'created_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function report()
    {
        return $this->belongsTo(
            IncidentReport::class,
            'report_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeInProgress($query)
    {
        return $query->where(
            'status',
            'IN_PROGRESS'
        );
    }
}