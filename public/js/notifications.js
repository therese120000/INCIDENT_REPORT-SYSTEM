$(function () {

    function loadNotificationCount() {

        $.ajax({
            url: '/notifications/count',
            method: 'GET',

            success: function (response) {

                if (!response.success) {
                    return;
                }

                const count = response.count || 0;

                $('#notificationCount').text(count);


                if (count > 0) {

                    $('#notificationCount')
                        .show();

                } else {

                    $('#notificationCount')
                        .hide();
                }
            },

            error: function (xhr) {

                console.error(
                    'Unable to load notification count:',
                    xhr.responseText
                );
            }
        });
    }

    function loadNotifications() {

        $.ajax({
            url: '/notifications',
            method: 'GET',

            success: function (response) {

                if (!response.success) {
                    return;
                }

                renderNotifications(
                    response.notifications
                );
            },

            error: function (xhr) {

                console.error(
                    'Unable to load notifications:',
                    xhr.responseText
                );
            }
        });
    }

    function renderNotifications(notifications) {

        const container =
            $('#notificationList');

        container.empty();


        if (!notifications || notifications.length === 0) {

            container.html(`
                <div class="notification-empty">
                    No notifications.
                </div>
            `);

            return;
        }


        notifications.forEach(function (notification) {

            const unreadClass =
                notification.is_read == 0
                    ? 'unread'
                    : '';


            const html = `

                <div
                    class="notification-item ${unreadClass}"
                    data-id="${notification.id}"
                >

                    <div class="notification-icon">
                        🔔
                    </div>


                    <div class="notification-content">

                        <div class="notification-title">
                            ${escapeHtml(notification.title)}
                        </div>


                        <div class="notification-message">
                            ${escapeHtml(notification.message)}
                        </div>


                        <div class="notification-date">
                            ${formatNotificationDate(
                                notification.created_at
                            )}
                        </div>

                    </div>

                </div>

            `;

            container.append(html);
        });
    }

    $('#notificationBtn').on('click', function (e) {

        e.stopPropagation();

        $('#notificationPanel')
            .toggleClass('show');

        if ($('#notificationPanel').hasClass('show')) {

            loadNotifications();
        }
    });

    $('#closeNotificationPanel').on('click', function () {

        $('#notificationPanel')
            .removeClass('show');
    });

    $(document).on(
        'click',
        '.notification-item',
        function () {

            const notificationId =
                $(this).data('id');

            const item =
                $(this);


            if (!item.hasClass('unread')) {
                return;
            }


            $.ajax({

                url:
                    `/notifications/${notificationId}/read`,

                method: 'PUT',

                headers: {

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]')
                            .attr('content')
                },

                success: function (response) {

                    if (!response.success) {
                        return;
                    }


                    item.removeClass('unread');


                    loadNotificationCount();
                },

                error: function (xhr) {

                    console.error(
                        'Unable to mark notification as read:',
                        xhr.responseText
                    );
                }

            });
        }
    );

    $(document).on('click', function (e) {

        if (
            !$(e.target).closest(
                '.notification-wrapper'
            ).length
        ) {

            $('#notificationPanel')
                .removeClass('show');
        }
    });

    function escapeHtml(text) {

        if (text === null || text === undefined) {
            return '';
        }

        return $('<div>')
            .text(text)
            .html();
    }

    function formatNotificationDate(dateString) {

        if (!dateString) {
            return '';
        }

        const date =
            new Date(dateString);

        return date.toLocaleString(
            'en-PH',
            {
                dateStyle: 'medium',
                timeStyle: 'short'
            }
        );
    }

    loadNotificationCount();

    setInterval(
        loadNotificationCount,
        10000
    );

});