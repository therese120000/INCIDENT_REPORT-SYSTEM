$(function () {

    function filterReports() {

        const searchValue =
            $('#reportSearch')
                .val()
                .toLowerCase()
                .trim();

        const issueValue =
            $('#issueFilter')
                .val()
                .toLowerCase()
                .trim();

        const statusValue =
            $('#statusFilter')
                .val()
                .toLowerCase()
                .trim();


        $('.reports-page table tbody tr').each(function () {

            const row = $(this);


            const reportId =
                row.find('td:eq(0)')
                    .text()
                    .toLowerCase()
                    .trim();

            const residentId =
                row.find('td:eq(1)')
                    .text()
                    .toLowerCase()
                    .trim();

            const issue =
                row.find('td:eq(2)')
                    .text()
                    .toLowerCase()
                    .trim();

            const location =
                row.find('td:eq(3)')
                    .text()
                    .toLowerCase()
                    .trim();

            const date =
                row.find('td:eq(4)')
                    .text()
                    .toLowerCase()
                    .trim();

            const status =
                row.find('td:eq(5)')
                    .text()
                    .toLowerCase()
                    .trim();



            const searchMatch =
                searchValue === '' ||
                reportId.includes(searchValue) ||
                residentId.includes(searchValue) ||
                issue.includes(searchValue) ||
                location.includes(searchValue) ||
                date.includes(searchValue) ||
                status.includes(searchValue);


            // const issueMatch =
            //     issueValue === '' ||
            //     issue === issueValue;

            const issueMatch =
                issueValue === '' ||
                issue.includes(issueValue);

            console.log({
                selectedIssue: issueValue,
                tableIssue: issue,
                issueMatch: issueMatch
            });

            const statusMatch =
                statusValue === '' ||
                status === statusValue;

            if (
                searchMatch &&
                issueMatch &&
                statusMatch
            ) {

                row.show();

            } else {

                row.hide();

            }

        });

    }


    $('#reportSearch').on(
        'input',
        function () {

            filterReports();

        }
    );


    $('#issueFilter').on(
        'change',
        function () {

            filterReports();

        }
    );


    $('#statusFilter').on(
        'change',
        function () {

            filterReports();

        }
    );


    

});