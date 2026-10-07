<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'address',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function localReports(){
        return $this->hasMany(LocalReport::class, 'user_id');
    }

    public function notifications() { 
        return $this->hasMany(Notification::class, 'user_id'); 
    }

    public function smsLogs() { 
        return $this->hasMany(SmsLog::class, 'user_id'); 
    }
}
