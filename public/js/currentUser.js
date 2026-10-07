$(function () {

    function loadCurrentUser() {

        $.ajax({

            url: '/current-user',

            method: 'GET',

            success: function (response) {

                if (!response.success || !response.user) {
                    return;
                }

                const user = response.user;

                const name = user.name || 'User';

                const firstName = name.trim().split(/\s+/)[0] || 'user';

                $('#loginName')
                    .text(name);

                $("#dashboard-label-name").text(firstName);


                $('#loginPosition')
                    .text(formatRole(user.role));


                const initials =
                    getInitials(name);

                $('#userAvatar')
                    .text(initials);

            },

            error: function (xhr) {

                console.error(
                    'Unable to get current user:',
                    xhr.responseText
                );

            }

        });

    }

    function getInitials(name) {

        if (!name) {
            return '--';
        }

        const parts = name
            .trim()
            .split(/\s+/)
            .filter(Boolean);


        if (parts.length === 0) {
            return '--';
        }

        if (parts.length === 1) {

            return parts[0]
                .substring(0, 2)
                .toUpperCase();

        }


        return (
            parts[0].charAt(0) +
            parts[parts.length - 1].charAt(0)
        ).toUpperCase();

    }


    function formatRole(role) {

        if (!role) {
            return '';
        }

        const normalized =
            role.toLowerCase();


        switch (normalized) {

            case 'admin':
                return 'Admin';

            case 'local':
                return 'Resident';

            case 'resident':
                return 'Resident';

            default:

                return role
                    .charAt(0)
                    .toUpperCase() +
                    role
                        .slice(1)
                        .toLowerCase();
        }

    }


    loadCurrentUser();

});