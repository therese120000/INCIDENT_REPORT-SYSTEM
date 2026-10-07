<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = 'sms_logs';

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'user_id',
        'contact_number',
        'message',
        'type',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

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
}
