<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Community Incident Report</title>
    <script src="{{ asset('libs/jquery.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2@11.js')}}"></script>

     <link rel="stylesheet" href="{{ asset('css/local.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
</head>

<body>
    <div class="local-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="community-logo">
                    IRS
                </div>

                <div class="community-name">
                    <h2>Purok Community</h2>
                    <span>Resident Portal</span>
                </div>
            </div>

            <nav class="local-navigation">
                <a href="" class="lui-nav-item active" data-page="dashboard" id="local-dashboard-btn">
                    <span class="nav-icon">⌂</span>
                    <span>Dashboard</span>
                </a>

                <a href="" class="lui-nav-item" data-page="report" id="local-rprt-btn">
                    <span class="nav-icon">＋</span>
                    <span>Report</span>
                </a>

                <a href="" class="lui-nav-item" data-page="history" id="local-hist-btn">
                    <span class="nav-icon">◷</span>
                    <span>History</span>
                </a>
            </nav>

            <!-- SIDEBAR FOOTER -->
            <div class="sidebar-footer">
                <!-- <a href="#" class="lui-nav-item">
                    <span class="nav-icon">⚙</span>
                    <span>Account</span>
                </a> -->

                <form method="POST" action="/auth/logout" class="logout-form">
                    @csrf

                    <button type="submit" class="lui-nav-item logout">
                        <span class="nav-icon">↪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div class="page-title">
                    <h1 id="local-pageTitle">Dashboard</h1>
                    <p id="local-pageDescription">
                        Overview of your submitted community reports.
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

            <section class="localui-content-area">

                <div class="page dashboard-page active-page" id="dashboard">
                    <div class="welcome-section">
                        <h2>Welcome back, <span id="dashboard-label-name">Christian</span>!</h2>
                        <p>
                            Thank you for helping keep our community safe
                            and informed.
                        </p>
                        <button class="primary-btn" id="local-report-btn"  data-page="report">
                            + Report an Issue
                        </button>
                    </div>

                    <div class="stats-container">

                        <div class="stat-card">
                            <div class="stat-icon">📝</div>
                            <div>
                                <span>Total Reports</span>
                                <strong id="totalReports">0</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">🔍</div>
                            <div>
                                <span>Under Review</span>
                                <strong id="pendingReports">0</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">🔧</div>
                            <div>
                                <span>In Progress</span>
                                <strong id="inProgressReports">0</strong>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">✓</div>
                            <div>
                                <span>Resolved</span>
                                <strong id="resolvedReports">0</strong>
                            </div>
                        </div>

                    </div>

                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2>Recent Reports - Weekly</h2>
                                <p>Your latest submitted reports</p>
                            </div>
                            <button class="text-btn" data-page="history" id="view-all-resident-reports">
                                View All
                            </button>
                        </div>

                        <div class="report-list" id="recentReportsList">
                            <p>Loading reports...</p>
                        </div>
                    </div>
                </div>
                
            </section>
        </main>
    </div>
    <script src="{{ asset('js/utility.js') }}"></script>
    <script src="{{ asset('js/local-toggle.js') }}"></script>
    <script src="{{ asset('js/local-dashboard.js')}}"></script>
    <script src="{{ asset('js/local-history.js')}}"></script>
    <script src="{{ asset('js/submitReport.js')}}"></script>
    <script src="{{ asset('js/alert.js')}}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/currentUser.js')}}"></script>


</body>

</html>