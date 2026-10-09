<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LocalDashboardController;
use App\Http\Controllers\LocalRegisterController;
use App\Http\Controllers\LocalReportController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/auth/login', [
    LoginController::class,
    'login'
]);

Route::post('/auth/logout', [
    LoginController::class,
    'logout'
]);


Route::middleware(['auth', 'role:LOCAL'])->group(function () {

    Route::get('/local/dashboard', function () {
        return view('local.dashboard');
    });

    Route::get('/local/dashboard/data', [
        LocalDashboardController::class,
        'dashboardData'
    ]);

    Route::get('/local/report', [
        LocalReportController::class,
        'create'
    ])->name('local.report');


    Route::post('/local/report', [
        IncidentReportController::class,
        'store'
    ])->name('local.report.store');

    Route::get('/local/history', function () {
        return view('local.history');
    });

    Route::get('/local/reports/{id}', [
        IncidentReportController::class,
        'localDetails'
    ])->name('local.reports.details');

});



Route::middleware(['auth', 'role:ADMIN'])->group(function () {

    Route::get('/admin/dashboard', [
        AdminController::class,
        'dashboard'
    ])->name('admin.dashboard');


    Route::get('/admin/dashboard/data', [
        AdminController::class,
        'dashboardData'
    ])->name('admin.dashboard.data');

    Route::get('/admin/reports/generate-data', [
        AdminController::class,
        'generateReportData'
    ])->name('admin.reports.generateData');

    Route::post('/admin/reports/generate', [
        AdminController::class,
        'generateReport'
    ])->name('admin.reports.generate');


    Route::get('/admin/users', [
        UserController::class,
        'index'
    ]);


    Route::get('/admin/users/{id}', [
        UserController::class,
        'getUser'
    ]);


    Route::post('/admin/users', [
        UserController::class,
        'store'
    ])->name('admin.users.store');


    Route::put('/admin/users/{id}', [
        UserController::class,
        'updateUser'
    ]);


    Route::get('/admin/reports', [
        IncidentReportController::class,
        'index'
    ]);


    Route::get('/admin/reports/{id}', [
        IncidentReportController::class,
        'adminDetails'
    ])->name('admin.reports.details');


    Route::put('/admin/reports/{report}/status', [
        IncidentReportController::class,
        'updateStatus'
    ])->name('admin.reports.updateStatus');

    
});

Route::get('/auth/register', function () {
    return view('auth.register');
});

Route::post('/auth/register', [
    LocalRegisterController::class,
    'register'
]);

Route::middleware('auth')->group(function () {

    Route::get('/current-user', function (Illuminate\Http\Request $request) {

        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    });

    Route::get('/notifications/count', [NotificationController::class, 'count']);

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

});