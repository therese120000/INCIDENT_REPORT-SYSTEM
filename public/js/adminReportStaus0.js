function formatReportDate(dateValue){
    if (!dateValue) {
        return '-';
    }

    const date = new Date(dateValue);

    if (isNaN(date.getTime())) {
        return '-';
    }

    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit'
    });
}

function loadReportPhoto(photoUrl)
{
    if (photoUrl) {

        $('#modalReportPhoto')
            .attr('src', photoUrl)
            .show();

        $('#noReportPhoto').hide();

    } else {

        $('#modalReportPhoto')
            .hide()
            .attr('src', '');

        $('#noReportPhoto').show();
    }
}

function loadReportPhoto(photoUrl)
{
    if (photoUrl) {

        $('#modalReportPhoto')
            .attr('src', photoUrl)
            .show();

        $('#noReportPhoto').hide();

    } else {

        $('#modalReportPhoto')
            .hide()
            .attr('src', '');

        $('#noReportPhoto').show();
    }
}

function escapeHtml(value)
{
    if (value === null || value === undefined) {
        return '';
    }

    return $('<div>')
        .text(value)
        .html();
}

function toggleStatusFields()
{
    const status = $('#reportStatus').val();

    $('#progressMessageGroup').hide();
    $('#actionTakenGroup').hide();

    $('#progressMessage')
        .prop('required', false);

    $('#actionTaken')
        .prop('required', false);

    if (
        status === 'IN_PROGRESS' ||
        status === 'RESOLVED'
    ) {

        $('#progressMessageGroup').show();

        $('#actionTakenGroup').show();

        $('#progressMessage')
            .prop('required', true);

        $('#actionTaken')
            .prop('required', true);
    }
}

function loadReportProgress(report)
{
    const timeline =
        $('#reportProgressTimeline');

    timeline.empty();

    /*
    -----------------------------------------
    SUBMITTED
    -----------------------------------------
    */

    timeline.append(`
        <div class="timeline-item submitted">

            <div class="timeline-status">
                Submitted
            </div>

            <div class="timeline-date">
                ${formatReportDate(report.submitted_at)}
            </div>

        </div>
    `);


    /*
    -----------------------------------------
    ACKNOWLEDGED
    -----------------------------------------
    */

    if (report.acknowledged_at) {

        timeline.append(`
            <div class="timeline-item acknowledged">

                <div class="timeline-status">
                    Acknowledged
                </div>

                <div class="timeline-date">
                    ${formatReportDate(report.acknowledged_at)}
                </div>

                <div class="timeline-message">
                    Report acknowledged by the barangay.
                </div>

            </div>
        `);
    }


    /*
    -----------------------------------------
    IN PROGRESS / RESOLVED
    -----------------------------------------
    */

    if (
        report.updates &&
        report.updates.length > 0
    ) {

        report.updates.forEach(function (update) {

            let html = `

                <div class="timeline-item">

                    <div class="timeline-status">
                        ${formatStatus(update.status)}
                    </div>

                    <div class="timeline-date">
                        ${formatReportDate(update.created_at)}
                    </div>
            `;


            if (update.message) {

                html += `

                    <div class="timeline-message">

                        <strong>
                            Message:
                        </strong>

                        ${escapeHtml(update.message)}

                    </div>
                `;
            }


            if (update.action_taken) {

                html += `

                    <div class="timeline-action">

                        <strong>
                            Action Taken:
                        </strong>

                        ${escapeHtml(update.action_taken)}

                    </div>
                `;
            }


            html += `

                </div>
            `;


            timeline.append(html);

        });
    }
}

$(document).on('click', '.view-btn', function () {

    const reportId = $(this).data('id');

    if (!reportId) {
        showMsg(
            "error",
            "Report ID is missing."
        );

        return;
    }

    $('#reportId').val(reportId);

    // Reset modal
    $('#modalReportNumber').text('-');
    $('#modalReportResident').text('-');
    $('#modalIncidentType').text('-');
    $('#modalReportTitle').text('-');
    $('#modalReportDescription').text('-');
    $('#modalReportLocation').text('-');
    $('#modalReportPurok').text('-');
    $('#modalReportWaterLevel').text('-');
    $('#modalReportSeverity').text('-');
    $('#modalReportCurrentStatus').text('-');
    $('#modalReportSubmittedAt').text('-');

    $('#modalReportPhoto')
        .hide()
        .attr('src', '');

    $('#noReportPhoto').show();

    $('#reportProgressTimeline').html(
        '<p>Loading progress...</p>'
    );

    $('#reportStatusModal').show();

    $.ajax({

        url: '/reports/' + reportId + '/details',

        type: 'GET',

        success: function (response) {

            if (!response.success) {

                showMsg(
                    "error",
                    response.message ||
                    "Unable to load report details."
                );

                return;
            }

            const report = response.report;

            $('#modalReportNumber')
                .text(report.report_number || '-');

            $('#modalReportResident')
                .text(report.resident || '-');

            $('#modalIncidentType')
                .text(report.incident_type || '-');

            $('#modalReportTitle')
                .text(report.title || '-');

            $('#modalReportDescription')
                .text(report.description || '-');

            $('#modalReportLocation')
                .text(report.location || '-');

            $('#modalReportPurok')
                .text(report.purok || '-');

            $('#modalReportWaterLevel')
                .text(report.water_level || '-');

            $('#modalReportSeverity')
                .text(report.severity || '-');

            $('#modalReportCurrentStatus')
                .text(report.status || '-');

            $('#modalReportSubmittedAt')
                .text(
                    formatReportDate(
                        report.submitted_at
                    )
                );

            $('#reportStatus')
                .val(report.status || 'SUBMITTED');

            $('#progressMessage').val('');
            $('#actionTaken').val('');

            toggleStatusFields();

            loadReportPhoto(report.photo);

            loadReportProgress(report);

        },

        error: function (xhr) {

            console.log(
                'STATUS:',
                xhr.status
            );

            console.log(
                'RESPONSE:',
                xhr.responseText
            );

            console.log(
                'JSON:',
                xhr.responseJSON
            );

            let message =
                'Unable to update report status.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.error
            ) {

                message =
                    xhr.responseJSON.error;

            }

            else if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;
            }

            showMsg(
                "error",
                message
            );
        },

    });

});

$('#cancelReportStatus').on(
    'click',
    function () {

        $('#reportStatusModal').hide();

    }
);
