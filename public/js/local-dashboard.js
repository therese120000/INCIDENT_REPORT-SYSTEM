function loadDashboardData() {

    $.ajax({
        url: '/local/dashboard/data',
        type: 'GET',

        success: function (response) {

            console.log('Dashboard data:', response);


            // =========================================
            // USER
            // =========================================

            $('.user-info strong').text(
                response.user.name
            );


            // =========================================
            // STATISTICS
            // =========================================

            $('#totalReports').text(
                response.statistics.total
            );

            $('#pendingReports').text(
                response.statistics.under_review
            );

            $('#inProgressReports').text(
                response.statistics.in_progress
            );

            $('#resolvedReports').text(
                response.statistics.resolved
            );


            // =========================================
            // RECENT REPORTS
            // =========================================

            loadRecentReports(
                response.week.reports
            );

        },

        error: function (xhr) {

            console.error(
                'Dashboard error:',
                xhr.status,
                xhr.responseText
            );

        }
    });

}

function loadRecentReports(reports) {

    const container = $('#recentReportsList');

    container.empty();

    if (!reports || reports.length === 0) {

        container.html(`
            <p class="empty-message">
                No reports submitted this week.
            </p>
        `);

        return;
    }


    reports.forEach(function (report) {

        const date = new Date(report.created_at);

        const formattedDate = date.toLocaleDateString(
            'en-US',
            {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }
        );


        container.append(`

            <div class="report-item">

                <div class="report-info">

                    <strong>
                        ${report.report_number}
                    </strong>

                    <h3>
                        ${report.incident_type?.name ?? report.title}
                    </h3>

                    <span>
                        📍 ${report.purok ?? report.location}
                    </span>

                    <small>
                        Submitted ${formattedDate}
                    </small>

                </div>

                <span class="status ${getStatusClass(report.status)}">
                    ${formatStatus(report.status)}
                </span>

            </div>

        `);

    });

}

function loadRecentReports(reports) {

    const container = $('#recentReportsList');

    container.empty();

    if (!reports || reports.length === 0) {

        container.html(`
            <p class="empty-message">
                No reports submitted this week.
            </p>
        `);

        return;
    }


    reports.forEach(function (report) {

        const date = new Date(report.created_at);

        const formattedDate = date.toLocaleDateString(
            'en-US',
            {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            }
        );


        container.append(`

            <div class="report-item">

                <div class="report-info">

                    <strong>
                        ${report.report_number}
                    </strong>

                    <h3>
                        ${report.incident_type?.name ?? report.title}
                    </h3>

                    <span>
                        📍 ${report.purok ?? report.location}
                    </span>

                    <small>
                        Submitted ${formattedDate}
                    </small>

                </div>

                <span class="status ${getStatusClass(report.status)}">
                    ${formatStatus(report.status)}
                </span>

            </div>

        `);

    });

}

$(function(){
    loadDashboardData();
})