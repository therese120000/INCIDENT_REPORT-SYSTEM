
$(function () {

    $(document).on('click', '.view-btn', function () {
        const reportId = $(this).data('id');

        if (!reportId) {

            showMsg(
                'error',
                'Report ID is missing.'
            );

            return;
        }

        $('#modalReportPhoto')
            .hide()
            .attr('src', '');

        $('#noReportPhoto')
            .show();

        $('#reportProgressTimeline')
            .html('<p>Loading updates...</p>');



        $('#reportStatusModal').show();


        $.ajax({

            url:
                '/admin/reports/' +
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


                $('#reportId')
                    .val(report.id);

                $('#modalReportNumber')
                    .text(report.report_number || '-');

                $('#modalReportResident')
                    .text(
                        report.user?.name || '-'
                    );

                $('#modalIncidentType')
                    .text(
                        report.incident_type?.name || '-'
                    );

                $('#modalReportTitle')
                    .text(report.title || '-');

                $('#modalReportDescription')
                    .text(
                        report.description || '-'
                    );

                $('#modalReportLocation')
                    .text(
                        report.location || '-'
                    );

                $('#modalReportPurok')
                    .text(
                        report.purok || '-'
                    );

                $('#modalReportWaterLevel')
                    .text(
                        report.water_level || '-'
                    );

                $('#modalReportSeverity')
                    .text(
                        report.severity || '-'
                    );

                $('#modalReportCurrentStatus')
                    .text(
                        report.status || '-'
                    );

                $('#modalReportSubmittedAt')
                    .text(
                        report.submitted_at || '-'
                    );


                $('#reportStatus')
                    .val(
                        report.status ||
                        'SUBMITTED'
                    );

                $('#progressMessage')
                    .val('');

                $('#actionTaken')
                    .val('');


                toggleStatusFields();

                loadAdminReportPhoto(
                    report
                );

                loadAdminReportUpdates(
                    report.updates || []
                );

            },

            error: function (xhr) {

                console.log(
                    'DETAIL STATUS:',
                    xhr.status
                );

                console.log(
                    'DETAIL RESPONSE:',
                    xhr.responseText
                );


                showMsg(
                    'error',
                    xhr.responseJSON?.message ||
                    'Unable to load report details.'
                );

                $('#reportStatusModal').hide();
            }

        });

    });


    function loadAdminReportPhoto(report) {

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

            $('#modalReportPhoto')
                .hide()
                .attr('src', '');

            $('#noReportPhoto')
                .show();

            return;
        }


        const photoUrl =
            '/storage/' +
            photo.file_path;


        $('#modalReportPhoto')
            .attr('src', photoUrl)
            .show();

        $('#noReportPhoto')
            .hide();

    }



    function loadAdminReportUpdates(updates) {

        const container =
            $('#reportProgressTimeline');


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



    function toggleStatusFields() {

        const status =
            $('#reportStatus').val();


        $('#progressMessageGroup')
            .hide();

        $('#actionTakenGroup')
            .hide();


        $('#progressMessage')
            .prop('required', false);

        $('#actionTaken')
            .prop('required', false);


        if (
            status === 'IN_PROGRESS' ||
            status === 'RESOLVED'
        ) {

            $('#progressMessageGroup')
                .show();

            $('#actionTakenGroup')
                .show();


            $('#progressMessage')
                .prop('required', true);

            $('#actionTaken')
                .prop('required', true);
        }

    }


    $('#reportStatus').on(
        'change',
        function () {

            toggleStatusFields();

        }
    );



    $('#closeReportStatusModal').on(
        'click',
        function () {

            $('#reportStatusModal').hide();

        }
    );


    $('#cancelReportStatus').on(
        'click',
        function () {

            $('#reportStatusModal').hide();

        }
    );


    $('#reportStatusForm').on(
        'submit',
        function (e) {

            e.preventDefault();


            const reportId =
                $('#reportId').val();

            const status =
                $('#reportStatus').val();

            const message =
                $('#progressMessage').val();

            const actionTaken =
                $('#actionTaken').val();


            if (!reportId) {

                showMsg(
                    'error',
                    'Report ID is missing.'
                );

                return;
            }


            if (
                status === 'IN_PROGRESS' ||
                status === 'RESOLVED'
            ) {

                if (!message.trim()) {

                    showMsg(
                        'warning',
                        'Please enter a message.'
                    );

                    $('#progressMessage')
                        .focus();

                    return;
                }


                if (!actionTaken.trim()) {

                    showMsg(
                        'warning',
                        'Please enter the action taken.'
                    );

                    $('#actionTaken')
                        .focus();

                    return;
                }

            }


            const saveButton =
                $('#saveReportStatus');


            saveButton
                .prop('disabled', true)
                .text('Updating...');


            $.ajax({

                url:
                    '/admin/reports/' +
                    reportId +
                    '/status',

                type: 'PUT',

                data: {

                    _token:
                        $('meta[name="csrf-token"]')
                        .attr('content'),

                    status:
                        status,

                    message:
                        message,

                    action_taken:
                        actionTaken

                },


                success: function (response) {

                    if (response.success) {

                        showMsg(
                            'success',
                            response.message
                        );

                        $('#reportStatusModal')
                            .hide();


                        setTimeout(function () {

                            location.reload();

                        }, 2500);

                    } else {

                        showMsg(
                            'error',
                            response.message ||
                            'Unable to update report status.'
                        );

                    }

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


                    showMsg(
                        'error',
                        xhr.responseJSON?.error ||
                        xhr.responseJSON?.message ||
                        'Unable to update report status.'
                    );

                },


                complete: function () {

                    saveButton
                        .prop('disabled', false)
                        .text('Update Status');

                }

            });

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