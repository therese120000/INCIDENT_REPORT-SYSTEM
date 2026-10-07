<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'report_id',
        'title',
        'message',
        'type',
        'is_read',
        'created_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function report()
    {
        return $this->belongsTo(
            IncidentReport::class,
            'report_id'
        );
    }
}
