<?php

namespace App\Http\Controllers;

use App\Models\IncidentType;

class LocalReportController extends Controller
{
    public function create()
    {
        $incidentTypes = IncidentType::all();

        return view(
            'local.report',
            compact('incidentTypes')
        );
    }
}
