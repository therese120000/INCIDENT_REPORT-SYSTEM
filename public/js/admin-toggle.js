$(document).on('click', '.navigation .nav-item', function (e) {

    e.preventDefault();

    const contentArea = $('.content-area');
    const clickedButton = $(this);

    $('.navigation .nav-item').removeClass('active');

    clickedButton.addClass('active');

    switch (this.id) {

        case 'adm-dashboard-btn':
            window.location.href = '/admin/dashboard';
            break;

        case 'adm-rprt-btn':
            contentArea.load('/admin/reports', function (response, status, xhr) {

                if (status === 'error') {
                    contentArea.html(
                        '<p>Failed to load reports.</p>'
                    );

                    console.error(
                        'Reports loading error:',
                        xhr.status,
                        xhr.statusText
                    );
                }

            });

            $("#pageTitle").text("Reports");
            $("#pageDescription").text("Review and manage reports submitted by residents.");

            break;

        case 'adm-users-btn':
            contentArea.load('/admin/users', function (response, status, xhr) {

                if (status === 'error') {
                    contentArea.html(
                        '<p>Failed to load users.</p>'
                    );

                    console.error(
                        'Users loading error:',
                        xhr.status,
                        xhr.statusText
                    );
                }

            });

            $("#pageTitle").text("Users");
            $("#pageDescription").text("Manage registered residents.");

            break;
    }

});

$(document).on('click', '#view-all-admin-reports', function(){
    $('#adm-rprt-btn').trigger('click');
});