$(function(){
    $('#incidentReportForm').on('submit', function(e) {

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        $.ajax({
            // url: "{{ route('local.report.store') }}",
            url: "/local/report",
            method: "POST",
            data: formData,

            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {

                if (response.success) {

                    // alert(
                    //     'Report submitted successfully.\n\n' +
                    //     'Report Number: ' +
                    //     response.report_number
                    // );

                    showMsg("success", 'Report submitted successfully.');

                    form.reset();

                }
            },

            error: function(xhr) {

                if (xhr.status === 422) {

                    const errors = xhr.responseJSON.errors;

                    let message = '';

                    Object.keys(errors).forEach(function(field) {

                        message +=
                            errors[field][0] + '\n';

                    });

                    // alert(message);
                    showMsg("error", message);

                } else {

                    // alert(
                    //     xhr.responseJSON?.message ||
                    //     'Unable to submit report.'
                    // );
                    
                    showMsg("error", xhr.responseJSON?.message ||
                        'Unable to submit report.');
                }
            }
        });

    });
});

