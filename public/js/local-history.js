let historyReports = [];

function loadHistoryReports() {

    $.ajax({
        url: '/local/dashboard/data',
        type: 'GET',

        success: function (response) {

            historyReports = response.all_reports || [];

            filterHistoryReports();

        },

        error: function (xhr) {

            console.error(
                'History error:',
                xhr.status,
                xhr.responseText
            );

        }
    });

}

function filterHistoryReports() {

    const searchValue = String(
        $('#reportSearch').val() || ''
    )
        .toLowerCase()
        .trim();


    const statusValue = String(
        $('#statusFilter').val() || ''
    )
        .toUpperCase()
        .trim();


    const filteredReports = historyReports.filter(function (report) {

        const reportNumber =
            String(report.report_number ?? '');

        const incidentType =
            String(
                report.incident_type?.name ??
                report.title ??
                ''
            );

        const purok =
            String(report.purok ?? '');

        const location =
            String(report.location ?? '');

        const status =
            String(report.status ?? '');

        const date =
            String(report.created_at ?? '');


        const searchableText = [

            reportNumber,
            incidentType,
            purok,
            location,
            status,
            date

        ]
            .join(' ')
            .toLowerCase();


        const matchesSearch =
            searchableText.includes(searchValue);


        const matchesStatus =
            statusValue === '' ||
            status === statusValue;


        return matchesSearch && matchesStatus;

    });


    renderHistoryReports(filteredReports);

}

function renderHistoryReports(reports) {

    const tableBody = $('#historyReportsBody');

    tableBody.empty();


    if (!reports || reports.length === 0) {

        tableBody.html(`
            <tr>
                <td colspan="6">
                    No reports found.
                </td>
            </tr>
        `);

        return;
    }


    reports.forEach(function (report) {

        const date = new Date(report.created_at);

        const formattedDate =
            date.toLocaleDateString(
                'en-US',
                {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }
            );


        tableBody.append(`

            <tr>

                <td>
                    ${report.report_number}
                </td>

                <td>
                    ${report.incident_type?.name ?? report.title}
                </td>

                <td>
                    ${report.purok ?? report.location}
                </td>

                <td>
                    ${formattedDate}
                </td>

                <td>
                    <span class="status ${getStatusClass(report.status)}">
                        ${formatStatus(report.status)}
                    </span>
                </td>

                <td>
                    <button
                        type="button"
                        class="local-view-btn"
                        data-id="${report.id}"
                    >
                        View
                    </button>
                </td>

            </tr>

        `);

    });

}


$(document).on(
    'input',
    '#reportSearch',
    function () {

        filterHistoryReports();

    }
);



$(document).on(
    'change',
    '#statusFilter',
    function () {

        filterHistoryReports();

    }
);



$(function () {

    loadHistoryReports();

});