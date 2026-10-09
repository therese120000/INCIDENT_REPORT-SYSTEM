$(function () {

    const monthLabels = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    const canvas =
        document.getElementById('monthlyReportChart');


    if (!canvas) {
        return;
    }


    const ctx =
        canvas.getContext('2d');


    const monthlyChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels: monthLabels,

            datasets: [

                {

                    label: 'Incident Reports',

                    data:
                        window.monthlyReportData || [],

                    borderWidth: 3,

                    tension: 0.3,

                    fill: true

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: true,
                    position: 'top'
                },

                tooltip: {
                    enabled: true
                }

            },

            scales: {

                x: {

                    title: {
                        display: true,
                        text: 'Month'
                    }

                },

                y: {

                    beginAtZero: true,

                    title: {
                        display: true,
                        text: 'Number of Reports'
                    },

                    ticks: {
                        stepSize: 1
                    }

                }

            }

        }

    });


    window.currentDashboardData = {

        totalReports: 0,

        newReports: 0,

        inProgressReports: 0,

        resolvedReports: 0,

        monthlyReportData:
            window.monthlyReportData || []

    };

    function updateDashboard() {

        const from =
            $('#reportFromDate').val();

        const to =
            $('#reportToDate').val();


        if (!from || !to) {
            return;
        }


        if (from > to) {

            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date Range',
                text:
                    'The From date cannot be later than the To date.'
            });

            return;
        }


        $.ajax({

            url: '/admin/dashboard/data',

            method: 'GET',

            data: {
                from: from,
                to: to
            },

            success: function (response) {

                if (!response.success) {
                    return;
                }


                $('.stat-card')
                    .eq(0)
                    .find('strong')
                    .text(
                        response.totalReports
                    );


                $('.stat-card')
                    .eq(1)
                    .find('strong')
                    .text(
                        response.newReports
                    );


                $('.stat-card')
                    .eq(2)
                    .find('strong')
                    .text(
                        response.inProgressReports
                    );


                $('.stat-card')
                    .eq(3)
                    .find('strong')
                    .text(
                        response.resolvedReports
                    );

                $('.status-row')
                    .eq(0)
                    .find('strong')
                    .text(
                        response.newReports
                    );


                $('.status-row')
                    .eq(1)
                    .find('strong')
                    .text(
                        response.inProgressReports
                    );


                $('.status-row')
                    .eq(2)
                    .find('strong')
                    .text(
                        response.resolvedReports
                    );


                const total =
                    response.totalReports;


                let newPercent = 0;

                let progressPercent = 0;

                let resolvedPercent = 0;


                if (total > 0) {

                    newPercent =
                        (
                            response.newReports /
                            total
                        ) * 100;


                    progressPercent =
                        (
                            response.inProgressReports /
                            total
                        ) * 100;


                    resolvedPercent =
                        (
                            response.resolvedReports /
                            total
                        ) * 100;
                }


                $('.bar-new').css(
                    'width',
                    newPercent + '%'
                );


                $('.bar-progress').css(
                    'width',
                    progressPercent + '%'
                );


                $('.bar-resolved').css(
                    'width',
                    resolvedPercent + '%'
                );


                monthlyChart
                    .data
                    .datasets[0]
                    .data =
                        response.monthlyReportData;


                monthlyChart.update();


                window.currentDashboardData = {

                    totalReports:
                        response.totalReports,

                    newReports:
                        response.newReports,

                    inProgressReports:
                        response.inProgressReports,

                    resolvedReports:
                        response.resolvedReports,

                    monthlyReportData:
                        response.monthlyReportData
                };

            },

            error: function (xhr) {

                console.error(
                    'Dashboard statistics error:',
                    xhr.responseText
                );

            }

        });

    }

    $('#reportFromDate, #reportToDate')
        .on('change', function () {

            updateDashboard();

        });

});