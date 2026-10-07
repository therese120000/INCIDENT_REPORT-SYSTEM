$(document).on('click', '.local-navigation .lui-nav-item', function(e){
    e.preventDefault();
    const contentArea = $('.localui-content-area');
    const clickedButton = $(this);

    $('.lui-nav-item').removeClass('active');

    clickedButton.addClass('active');

    switch(this.id){
        case 'local-dashboard-btn':
            // contentArea.load('/local/dashboard');
            window.location.href = '/local/dashboard';

            break;
        case 'local-rprt-btn':
            contentArea.load('/local/report');
            $("#local-pageTitle").text("Reports");
            $("#local-pageDescription").text("Submit a community concern or incident that needs attention.");

            break;
        case 'local-hist-btn':
            contentArea.load('/local/history', function(){
                loadHistoryReports();
            });
            $("#local-pageTitle").text("History");
            $("#local-pageDescription").text("View and track all your submitted reports.");

            break;
    }
});

$(document).on('click', '#local-report-btn', function () {
    $('#local-rprt-btn').trigger('click');
});

$(document).on('click', '#view-all-resident-reports', function(){
    $('#local-hist-btn').trigger('click');
});

function getStatusClass(status) {

    switch (status) {

        case 'NEW':
            return 'new';

        case 'ACKNOWLEDGED':
        case 'UNDER_REVIEW':
            return 'under-review';

        case 'IN_PROGRESS':
            return 'in-progress';

        case 'RESOLVED':
            return 'resolved';

        default:
            return '';
    }
}


function formatStatus(status) {

    return status
        .replaceAll('_', ' ')
        .toLowerCase()
        .replace(/\b\w/g, function (letter) {
            return letter.toUpperCase();
        });
}