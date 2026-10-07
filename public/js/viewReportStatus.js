$(function () {

    $(document).on(
        'click',
        '.local-view-btn',
        function () {

            const reportId =
                $(this).data('id');


            if (!reportId) {

                showMsg(
                    'error',
                    'Report ID is missing.'
                );

                return;
            }

            $('#localModalReportPhoto')
                .hide()
                .attr('src', '');

            $('#localNoReportPhoto')
                .show();


            $('#localReportProgressTimeline')
                .html(
                    '<p>Loading updates...</p>'
                );


            $('#localReportDetailsModal')
                .show();


            $.ajax({

                url:
                    '/local/reports/' +
                    reportId,

                type: 'GET',

                success: function (response) {

                    if (!response.success) {

                        showMsg(
                            'error',
                            response.message ||
                            'Unable to load report.'
                        );

                        return;
                    }


                    const report =
                        response.report;


                    /*
                    |--------------------------------------------------------------------------
                    | DETAILS
                    |--------------------------------------------------------------------------
                    */

                    $('#localModalReportNumber')
                        .text(
                            report.report_number || '-'
                        );

                    $('#localModalReportResident')
                        .text(
                            report.user?.name || '-'
                        );

                    $('#localModalIncidentType')
                        .text(
                            report.incident_type?.name || '-'
                        );

                    $('#localModalReportTitle')
                        .text(
                            report.title || '-'
                        );

                    $('#localModalReportDescription')
                        .text(
                            report.description || '-'
                        );

                    $('#localModalReportLocation')
                        .text(
                            report.location || '-'
                        );

                    $('#localModalReportPurok')
                        .text(
                            report.purok || '-'
                        );

                    $('#localModalReportWaterLevel')
                        .text(
                            report.water_level || '-'
                        );

                    $('#localModalReportSeverity')
                        .text(
                            report.severity || '-'
                        );

                    $('#localModalReportCurrentStatus')
                        .text(
                            report.status || '-'
                        );

                    $('#localModalReportSubmittedAt')
                        .text(
                            report.submitted_at || '-'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | PHOTO
                    |--------------------------------------------------------------------------
                    */

                    loadLocalReportPhoto(
                        report
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATES
                    |--------------------------------------------------------------------------
                    */

                    loadLocalReportUpdates(
                        report.updates || []
                    );

                },

                error: function (xhr) {

                    console.log(
                        'LOCAL DETAIL STATUS:',
                        xhr.status
                    );

                    console.log(
                        'LOCAL DETAIL RESPONSE:',
                        xhr.responseText
                    );


                    showMsg(
                        'error',
                        xhr.responseJSON?.message ||
                        'Unable to load report details.'
                    );

                    $('#localReportDetailsModal')
                        .hide();

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PHOTO
    |--------------------------------------------------------------------------
    */

    function loadLocalReportPhoto(report) {

        const attachments =
            report.attachments || [];


        const photo =
            attachments.find(function (attachment) {

                return (
                    attachment.attachment_type ===
                    'PHOTO'
                );

            });


        if (!photo) {

            $('#localModalReportPhoto')
                .hide()
                .attr('src', '');

            $('#localNoReportPhoto')
                .show();

            return;
        }


        const photoUrl =
            '/storage/' +
            photo.file_path;


        $('#localModalReportPhoto')
            .attr('src', photoUrl)
            .show();

        $('#localNoReportPhoto')
            .hide();

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE HISTORY
    |--------------------------------------------------------------------------
    */

    function loadLocalReportUpdates(updates) {

        const container =
            $('#localReportProgressTimeline');


        if (!updates.length) {

            container.html(`
                <p>
                    No progress updates yet.
                </p>
            `);

            return;
        }


        let html = '';


        updates.forEach(function (update) {

            html += `

                <div class="progress-item">

                    <div class="progress-status">
                        ${formatStatus(update.status)}
                    </div>

                    <div class="progress-date">
                        ${update.created_at || ''}
                    </div>


                    <div class="progress-message">

                        <strong>
                            Message:
                        </strong>

                        <p>
                            ${escapeHtml(
                                update.message || '-'
                            )}
                        </p>

                    </div>


                    <div class="progress-action">

                        <strong>
                            Action Taken:
                        </strong>

                        <p>
                            ${escapeHtml(
                                update.action_taken || '-'
                            )}
                        </p>

                    </div>

                </div>

            `;

        });


        container.html(html);
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    $('#closeLocalReportDetails').on(
        'click',
        function () {

            $('#localReportDetailsModal')
                .hide();

        }
    );


    $('#closeLocalReportDetailsBottom').on(
        'click',
        function () {

            $('#localReportDetailsModal')
                .hide();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function formatStatus(status) {

        return String(status || '')
            .replaceAll('_', ' ')
            .replace(
                /\b\w/g,
                function (letter) {
                    return letter.toUpperCase();
                }
            );
    }


    function escapeHtml(value) {

        return $('<div>')
            .text(value)
            .html();

    }

});