$(function () {


    const COLORS = {

        yellow: '#FFD166',

        yellowDark: '#E5AE24',

        cream: '#FFFAF0',

        lightYellow: '#FFF4CC',

        veryLightYellow: '#FFF9E8',

        dark: '#292929',

        gray: '#666666',

        lightGray: '#E5E5E5',

        white: '#FFFFFF',

        green: '#4CAF50',

        blue: '#4A90E2',

        orange: '#F4A261',

        red: '#E76F51'

    };

    $('#generateReportBtn').on('click', async function () {

        const from =
            $('#reportFromDate').val();

        const to =
            $('#reportToDate').val();


        if (!from || !to) {

            Swal.fire({

                icon: 'warning',

                title: 'Date Range Required',

                text:
                    'Please select both From and To dates.'

            });

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

        Swal.fire({

            title: 'Generating Report',

            text:
                'Fetching incident reports...',

            allowOutsideClick: false,

            didOpen: () => {

                Swal.showLoading();

            }

        });


        try {

            const response = await $.ajax({

                url:
                    '/admin/reports/generate-data',

                method:
                    'GET',

                data: {

                    from:
                        from,

                    to:
                        to

                }

            });


            if (!response.success) {

                throw new Error(
                    'Unable to retrieve report data.'
                );

            }

            generatePDF(response);


        } catch (error) {

            console.error(
                'PDF generation error:',
                error
            );


            Swal.fire({

                icon: 'error',

                title: 'Report Generation Failed',

                text:
                    'Unable to generate the report. Please try again.'

            });

        }

    });

    function generatePDF(data) {

        const {
            jsPDF
        } = window.jspdf;

        const doc =
            new jsPDF({

                orientation:
                    'portrait',

                unit:
                    'mm',

                format:
                    'a4'

            });

        if (
            typeof doc.autoTable !== 'function'
        ) {

            throw new Error(
                'jsPDF AutoTable plugin is not loaded.'
            );

        }

        const pageWidth =
            doc.internal.pageSize.getWidth();

        const pageHeight =
            doc.internal.pageSize.getHeight();


        const margin = 10;

        doc.setFillColor(
            255,
            250,
            240
        );

        doc.rect(

            0,

            0,

            pageWidth,

            pageHeight,

            'F'

        );

        doc.setFillColor(
            255,
            209,
            102
        );

        doc.rect(

            0,

            0,

            pageWidth,

            34,

            'F'

        );

        // doc.setFillColor(
        //     41,
        //     41,
        //     41
        // );

        doc.rect(

            0,

            0,

            6,

            34,

            'F'

        );

        doc.setFont(
            'helvetica',
            'bold'
        );

        doc.setFontSize(
            17
        );

        doc.setTextColor(
            41,
            41,
            41
        );

        doc.text(

            'INCIDENT REPORT SYSTEM',

            margin,

            14

        );

        doc.setFont(
            'helvetica',
            'normal'
        );

        doc.setFontSize(
            7.5
        );

        doc.text(

            'Community Incident Monitoring & Management Report',

            margin,

            21

        );


        doc.setFont(
            'helvetica',
            'bold'
        );

        doc.setFontSize(
            7
        );

        doc.text(

            'REPORT PERIOD',

            pageWidth - margin,

            12,

            {
                align:
                    'right'
            }

        );

        doc.setFont(
            'helvetica',
            'normal'
        );

        doc.setFontSize(
            7
        );

        doc.text(

            `${data.fromDate} - ${data.toDate}`,

            pageWidth - margin,

            19,

            {
                align:
                    'right'
            }

        );



        doc.setFont(
            'helvetica',
            'bold'
        );

        doc.setFontSize(
            11
        );

        doc.setTextColor(
            41,
            41,
            41
        );

        doc.text(

            'REPORT SUMMARY',

            margin,

            44

        );


  

        const summaryY = 48;

        const cardGap = 4;

        const availableWidth =
            pageWidth -
            (margin * 2);

        const cardWidth =
            (
                availableWidth -
                cardGap
            ) / 2;

        const cardHeight = 23;


        const summary = [

            {

                label:
                    'TOTAL REPORTS',

                value:
                    data.totalReports,

                accent:
                    COLORS.yellow

            },

            {

                label:
                    'NOT ACKNOWLEDGED',

                value:
                    data.newReports,

                accent:
                    COLORS.orange

            },

            {

                label:
                    'IN PROGRESS',

                value:
                    data.inProgressReports,

                accent:
                    COLORS.blue

            },

            {

                label:
                    'RESOLVED',

                value:
                    data.resolvedReports,

                accent:
                    COLORS.green

            }

        ];


        summary.forEach(
            function (item, index) {

                const row =
                    Math.floor(
                        index / 2
                    );

                const column =
                    index % 2;


                const x =
                    margin +
                    (
                        cardWidth +
                        cardGap
                    ) * column;


                const y =
                    summaryY +
                    (
                        cardHeight +
                        cardGap
                    ) * row;


                doc.setFillColor(

                    255,

                    255,

                    255

                );

                doc.setDrawColor(

                    225,

                    225,

                    225

                );


                doc.roundedRect(

                    x,

                    y,

                    cardWidth,

                    cardHeight,

                    3,

                    3,

                    'FD'

                );


                let accentColor;


                if (
                    item.accent ===
                    COLORS.yellow
                ) {

                    accentColor = [

                        255,

                        209,

                        102

                    ];

                }

                else if (
                    item.accent ===
                    COLORS.orange
                ) {

                    accentColor = [

                        244,

                        162,

                        97

                    ];

                }

                else if (
                    item.accent ===
                    COLORS.blue
                ) {

                    accentColor = [

                        74,

                        144,

                        226

                    ];

                }

                else {

                    accentColor = [

                        76,

                        175,

                        80

                    ];

                }


                doc.setFillColor(
                    ...accentColor
                );


                doc.roundedRect(

                    x,

                    y,

                    4,

                    cardHeight,

                    3,

                    3,

                    'F'

                );

                doc.setTextColor(

                    41,

                    41,

                    41

                );

                doc.setFont(

                    'helvetica',

                    'bold'

                );

                doc.setFontSize(

                    17

                );


                doc.text(

                    String(
                        item.value
                    ),

                    x + 9,

                    y + 10

                );


                doc.setFont(

                    'helvetica',

                    'normal'

                );

                doc.setFontSize(

                    6.5

                );

                doc.setTextColor(

                    100,

                    100,

                    100

                );


                doc.text(

                    item.label,

                    x + 9,

                    y + 18

                );

            }
        );

        doc.setFont(

            'helvetica',

            'bold'

        );

        doc.setFontSize(

            11

        );

        doc.setTextColor(

            41,

            41,

            41

        );


        doc.text(

            'MONTHLY INCIDENT REPORTS',

            margin,

            105

        );

        const chartX =
            margin;

        const chartY =
            109;

        const chartWidth =
            pageWidth -
            (
                margin * 2
            );

        const chartHeight =
            57;


        doc.setFillColor(

            255,

            255,

            255

        );

        doc.setDrawColor(

            230,

            230,

            230

        );


        doc.roundedRect(

            chartX,

            chartY,

            chartWidth,

            chartHeight,

            3,

            3,

            'FD'

        );


        const chartImage =
            createChartImage(

                data.monthlyReportData

            );


        if (chartImage) {

            doc.addImage(

                chartImage,

                'PNG',

                chartX + 4,

                chartY + 4,

                chartWidth - 8,

                chartHeight - 8

            );

        }



        doc.setFont(

            'helvetica',

            'bold'

        );

        doc.setFontSize(

            11

        );

        doc.setTextColor(

            41,

            41,

            41

        );


        doc.text(

            'INCIDENT REPORT DETAILS',

            margin,

            175

        );

        doc.setFont(

            'helvetica',

            'normal'

        );

        doc.setFontSize(

            7

        );

        doc.setTextColor(

            100,

            100,

            100

        );


        doc.text(

            `${data.totalReports} incident report(s) found for the selected period.`,

            margin,

            181

        );


        const tableRows =
            data.reports.map(
                function (report) {

                    return [

                        report.report_number || '',

                        report.resident_name || '',

                        report.title || '',

                        report.description || '',

                        report.location || '',

                        report.water_level || '',

                        report.severity || '',

                        formatStatus(
                            report.status
                        ),

                        report.submitted_at || ''

                    ];

                }
            );

        doc.autoTable({

            startY:
                185,

            head: [[

                'REPORT NO.',

                'RESIDENT',

                'INCIDENT TITLE',

                'DESCRIPTION',

                'LOCATION',

                'WATER LEVEL',

                'SEVERITY',

                'STATUS',

                'DATE SUBMITTED'

            ]],


            body:
                tableRows,


            margin: {

                left:
                    margin,

                right:
                    margin,

                bottom:
                    14

            },


            theme:
                'grid',


            styles: {

                font:
                    'helvetica',

                fontSize:
                    5.8,

                textColor: [

                    55,

                    55,

                    55

                ],

                cellPadding:
                    2,

                overflow:
                    'linebreak',

                valign:
                    'middle',

                lineColor: [

                    225,

                    225,

                    225

                ],

                lineWidth:
                    0.2

            },

            headStyles: {

                fillColor: [

                    255,

                    209,

                    102

                ],

                textColor: [

                    41,

                    41,

                    41

                ],

                fontStyle:
                    'bold',

                fontSize:
                    5.8,

                halign:
                    'center',

                valign:
                    'middle',

                cellPadding:
                    2.5,

                lineColor: [

                    225,

                    185,

                    70

                ],

                lineWidth:
                    0.3

            },


            alternateRowStyles: {

                fillColor: [

                    255,

                    249,

                    232

                ]

            },


            bodyStyles: {

                fillColor: [

                    255,

                    255,

                    255

                ]

            },


            columnStyles: {

                0: {

                    cellWidth:
                        17,

                    halign:
                        'center'

                },


                1: {

                    cellWidth:
                        20

                },


                2: {

                    cellWidth:
                        23

                },


                3: {

                    cellWidth:
                        34

                },


                4: {

                    cellWidth:
                        21

                },


                5: {

                    cellWidth:
                        17,

                    halign:
                        'center'

                },


                6: {

                    cellWidth:
                        16,

                    halign:
                        'center'

                },


                7: {

                    cellWidth:
                        20,

                    halign:
                        'center'

                },


                8: {

                    cellWidth:
                        22,

                    halign:
                        'center'

                }

            },

            didParseCell:
                function (hookData) {

                    if (
                        hookData.section !==
                        'body'
                    ) {

                        return;

                    }


                    if (
                        hookData.column.index ===
                        7
                    ) {

                        const status =

                            String(

                                hookData.cell.raw ||
                                ''

                            ).toUpperCase();


                        hookData.cell.styles.fontStyle =
                            'bold';


                        if (
                            status ===
                            'RESOLVED'
                        ) {

                            hookData.cell.styles.textColor = [

                                46,

                                125,

                                50

                            ];

                        }

                        else if (
                            status ===
                            'IN PROGRESS'
                        ) {

                            hookData.cell.styles.textColor = [

                                25,

                                118,

                                210

                            ];

                        }


                        else if (
                            status ===
                            'NOT ACKNOWLEDGED'
                        ) {

                            hookData.cell.styles.textColor = [

                                230,

                                126,

                                34

                            ];

                        }



                        else if (
                            status ===
                            'ACKNOWLEDGED'
                        ) {

                            hookData.cell.styles.textColor = [

                                117,

                                90,

                                0

                            ];

                        }

                    }

                },


            didDrawPage:
                function () {

                    drawFooter(

                        doc,

                        pageWidth,

                        pageHeight,

                        margin

                    );

                }

        });



        const fileName =

            `incident-report-${fromDateForFile(data.fromDate)}-to-${fromDateForFile(data.toDate)}.pdf`;


        Swal.close();

        doc.save(

            fileName

        );

    }


    function createChartImage(monthlyData) {

        const canvas =
            document.createElement(
                'canvas'
            );


        canvas.width =
            1400;

        canvas.height =
            500;


        const context =
            canvas.getContext(
                '2d'
            );


        const labels = [

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


        const chart =

            new Chart(

                context,

                {

                    type:
                        'line',


                    data: {

                        labels:
                            labels,


                        datasets: [{

                            label:
                                'Incident Reports',

                            data:
                                monthlyData,

                            borderColor:
                                '#E5AE24',

                            backgroundColor:
                                'rgba(255, 209, 102, 0.25)',

                            borderWidth:
                                4,

                            tension:
                                0.35,

                            fill:
                                true,

                            pointRadius:
                                5,

                            pointHoverRadius:
                                7,

                            pointBackgroundColor:
                                '#FFD166',

                            pointBorderColor:
                                '#292929',

                            pointBorderWidth:
                                2

                        }]

                    },


                    options: {

                        responsive:
                            false,

                        animation:
                            false,


                        plugins: {

                            legend: {

                                display:
                                    true,

                                labels: {

                                    color:
                                        '#292929',

                                    font: {

                                        size:
                                            18

                                    }

                                }

                            }

                        },


                        scales: {

                            x: {

                                grid: {

                                    display:
                                        false

                                },

                                ticks: {

                                    color:
                                        '#666666',

                                    font: {

                                        size:
                                            14

                                    },

                                    maxRotation:
                                        0,

                                    minRotation:
                                        0

                                }

                            },


                            y: {

                                beginAtZero:
                                    true,

                                ticks: {

                                    precision:
                                        0,

                                    color:
                                        '#666666',

                                    font: {

                                        size:
                                            14

                                    }

                                },

                                grid: {

                                    color:
                                        'rgba(0,0,0,0.08)'

                                }

                            }

                        }

                    }

                }

            );


        const image =

            canvas.toDataURL(

                'image/png',

                1.0

            );


        chart.destroy();


        return image;

    }


    // =========================================================
    // FOOTER
    // =========================================================

    function drawFooter(

        doc,

        pageWidth,

        pageHeight,

        margin

    ) {

        const footerY =
            pageHeight - 7;


        // -----------------------------------------------------
        // FOOTER LINE
        // -----------------------------------------------------

        doc.setDrawColor(

            225,

            225,

            225

        );

        doc.setLineWidth(

            0.3

        );


        doc.line(

            margin,

            footerY - 4,

            pageWidth - margin,

            footerY - 4

        );


        // -----------------------------------------------------
        // FOOTER TEXT
        // -----------------------------------------------------

        doc.setFont(

            'helvetica',

            'normal'

        );

        doc.setFontSize(

            7

        );

        doc.setTextColor(

            110,

            110,

            110

        );


        // Generated date

        doc.text(

            `Generated on ${formatDate(new Date())}`,

            margin,

            footerY

        );


        // System name

        doc.text(

            'Incident Report System',

            pageWidth / 2,

            footerY,

            {

                align:
                    'center'

            }

        );


        // Page number

        doc.text(

            `Page ${doc.internal.getNumberOfPages()}`,

            pageWidth - margin,

            footerY,

            {

                align:
                    'right'

            }

        );

    }


    // =========================================================
    // FORMAT STATUS
    // =========================================================

    function formatStatus(status) {

        if (!status) {

            return '';

        }


        switch (

            status.toUpperCase()

        ) {

            case 'SUBMITTED':

                return 'Not Acknowledged';


            case 'ACKNOWLEDGED':

                return 'Acknowledged';


            case 'IN_PROGRESS':

                return 'In Progress';


            case 'RESOLVED':

                return 'Resolved';


            default:

                return status;

        }

    }


    // =========================================================
    // FORMAT DATE
    // =========================================================

    function formatDate(date) {

        return date.toLocaleDateString(

            'en-US',

            {

                year:
                    'numeric',

                month:
                    'long',

                day:
                    'numeric',

                hour:
                    'numeric',

                minute:
                    '2-digit'

            }

        );

    }


    // =========================================================
    // FILE DATE FORMAT
    // =========================================================

    function fromDateForFile(date) {

        return date

            .replace(
                /,/g,
                ''
            )

            .replace(
                /\s+/g,
                '-'
            );

    }

});