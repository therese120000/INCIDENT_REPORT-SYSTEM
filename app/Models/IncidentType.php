<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    protected $table = 'incident_types';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function reports()
    {
        return $this->hasMany(LocalReport::class, 'incident_type_id');
    }
}