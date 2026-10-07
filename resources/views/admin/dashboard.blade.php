<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | Incident Report System</title>
    <script src="{{ asset('libs/chart.js') }}"></script>
    <script src="{{ asset('libs/chart.min.js') }}"></script>
    <script src="{{ asset('libs/chartjs-plugin-datalabels.min.js') }}"></script>
    <script src="{{ asset('libs/chartjs-plugin-zoom.min.js') }}"></script>
    <script src="{{ asset('libs/jquery.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2@11.js')}}"></script>
    <script src="{{ asset('libs/chart.js')}}"></script>
    <script src="{{ asset('libs/chart.min.js')}}"></script>
    <script src="{{ asset('libs/chartjs-plugin-datalabels.min.js')}}"></script>
    <script src="{{ asset('libs/chartjs-plugin-zoom.min.js')}}"></script>
    <script src="{{ asset('libs/jspdf.umd.min.js')}}"></script>
    <script src="{{ asset('libs/jspdf.plugin.autotable.min.js')}}"></script>
    

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    
</head>

<body>
    <div class="admin-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="community-logo">
                    IRS
                </div>
                <div class="community-name">
                    <h2>Purok Community</h2>
                    <span>Barangay Portal</span>
                </div>

            </div>

            <!-- NAVIGATION -->
            <nav class="navigation">

                <a href="/admin/dashboard"
                class="nav-item active"
                data-page="dashboard"
                id="adm-dashboard-btn">

                    <span class="nav-icon">⌂</span>
                    <span>Dashboard</span>
                </a>

                <a href="/admin/reports"
                class="nav-item"
                data-page="reports"
                id="adm-rprt-btn">

                    <span class="nav-icon">▤</span>
                    <span>Reports</span>
                </a>

                <a href="/admin/users"
                class="nav-item"
                data-page="users"
                id="adm-users-btn">

                    <span class="nav-icon">♙</span>
                    <span>Users</span>
                </a>
            </nav>

            <!-- SIDEBAR FOOTER -->
            <div class="sidebar-footer">
                <!-- <a href="#" class="nav-item">
                    <span class="nav-icon">⚙</span>
                    <span>Settings</span>
                </a> -->

                <form method="POST" action="/auth/logout" class="logout-form">
                    @csrf

                    <button type="submit" class="nav-item logout">
                        <span class="nav-icon">↪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </aside>

        <main class="main-content">

            <header class="top-header">
                <div class="page-title">
                    <h1 id="pageTitle">
                        Dashboard
                    </h1>
                    <p id="pageDescription">
                        Overview of community incidents and reports.
                    </p>
                </div>

                <div class="header-right">
                    <div class="notification-wrapper">

                        <button
                            type="button"
                            class="notification-btn"
                            id="notificationBtn"
                        >
                            🔔
                            <span
                                class="notification-count"
                                id="notificationCount"
                            >
                                0
                            </span>
                        </button>


                        <!-- Notification dropdown -->
                        <div
                            class="notification-panel"
                            id="notificationPanel"
                        >

                            <div class="notification-panel-header">
                                <h3>Notifications</h3>

                                <button
                                    type="button"
                                    id="closeNotificationPanel"
                                >
                                    &times;
                                </button>
                            </div>


                            <div
                                class="notification-list"
                                id="notificationList"
                            >
                                <div class="notification-empty">
                                    No notifications.
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="user-profile">

                        <div class="user-avatar" id="userAvatar">
                            --
                        </div>

                        <div class="user-info">

                            <strong
                                class="login-name"
                                id="loginName"
                            >
                                Loading...
                            </strong>

                            <span
                                class="login-position"
                                id="loginPosition"
                            >
                                Loading...
                            </span>

                        </div>

                    </div>
                </div>
            </header>

            <section class="content-area">
                

                <div class="page dashboard-page active-page" id="dashboard">
                    <div class="welcome-section">

                        <div>
                            <h2>Community Incident Dashboard</h2>
                            <p>Monitor submitted reports and community concerns.</p>
                        </div>

                        <!-- <div class="dashboard-actions">
                            
                            <div class="dashboard-date">
                                October 1, 2026
                            </div>

                            <button class="generate-report-btn" id="generateReportBtn">
                                <span>▣</span>
                                Generate Report
                            </button>

                        </div> -->
                        
                        <div class="dashboard-actions">

                            <div class="dashboard-date">
                                <label for="reportFromDate">From</label>
                                <input
                                    type="date"
                                    id="reportFromDate"
                                    name="report_from_date"
                                >
                            </div>

                            <div class="dashboard-date">
                                <label for="reportToDate">To</label>
                                <input
                                    type="date"
                                    id="reportToDate"
                                    name="report_to_date"
                                >
                            </div>

                            <button class="generate-report-btn" id="generateReportBtn">
                                <span>▣</span>
                                Generate Report
                            </button>

                        </div>
                    </div>

                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-icon">▤</div>
                            <div>
                                <span>Total Reports</span>
                                <strong>{{ $totalReports }}</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">!</div>
                            <div>
                                <span>New Reports</span>
                                <strong>{{ $newReports }}</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">⚙</div>
                            <div>
                                <span>In Progress</span>
                                <strong>{{ $inProgressReports }}</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">✓</div>
                            <div>
                                <span>Resolved</span>
                                <strong>{{ $resolvedReports }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- REPORT OVERVIEW -->
                    <div class="dashboard-grid">
                        <div class="section-card">
                            <div class="section-header">
                                <div>
                                    <h2>Recent Reports - Weekly</h2>
                                    <p>Latest incidents submitted by residents.</p>
                                </div>
                                <button class="text-btn" data-page="reports" id="view-all-admin-reports" >View All</button>
                            </div>

                            <div class="report-list">

                                @foreach ($recentReports as $report)

                                    <div class="report-item">

                                        <div class="report-info">

                                            <strong>{{ $report->report_number }}</strong>

                                            <h3>{{ $report->title }}</h3>

                                            <span>📍 {{ $report->purok }}</span>

                                        </div>

                                        @if ($report->resolved_at)
                                            <span class="status resolved">
                                                Resolved
                                            </span>

                                        @elseif ($report->acknowledged_at)
                                            <span class="status in-progress">
                                                In Progress
                                            </span>

                                        @else
                                            <span class="status new">
                                                New
                                            </span>
                                        @endif

                                    </div>

                                @endforeach

                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-header">
                                <div>
                                    <h2>Report Status</h2>
                                    <p>Current report distribution.</p>
                                </div>
                            </div>

                            <div class="status-overview">

                                <div class="status-row">
                                    <span>New</span>
                                    <strong>{{ $newReports }}</strong>
                                </div>

                                <div class="status-bar">
                                    <span class="bar-new"></span>
                                </div>


                                <div class="status-row">
                                    <span>In Progress</span>
                                    <strong>{{ $inProgressReports }}</strong>
                                </div>

                                <div class="status-bar">
                                    <span class="bar-progress"></span>
                                </div>


                                <div class="status-row">
                                    <span>Resolved</span>
                                    <strong>{{ $resolvedReports }}</strong>
                                </div>

                                <div class="status-bar">
                                    <span class="bar-resolved"></span>
                                </div>

                            </div>
                        </div>

                        <div class="section-card chart-cntr">
                            <div class="section-header">
                                <div>
                                    <h2>Report Overall Chart</h2>
                                    <p>Overall report distribution.</p>
                                </div>
                            </div>

                            <div class="monthly-report-chart">
                                <h3>Monthly Incident Report</h3>

                                <canvas id="monthlyReportChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/utility.js') }}"></script>
    <script src="{{ asset('js/admin-toggle.js') }}"></script>
    <script src="{{ asset('js/alert.js')}}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/currentUser.js')}}"></script>

    <script>
        window.monthlyReportData = @json($monthlyReportData);
    </script>
    <script src="{{ asset('js/overall-chart.js') }}"></script>
    <script src="{{ asset('js/generate-report.js') }}"></script>

</body>

</html>