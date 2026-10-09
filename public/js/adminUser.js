$(function () {
    $(document).on('click', '.user-view-btn', function () {

        const userId = $(this).data('id');

        if (!userId) {
            showMsg(
                "error",
                "User ID is missing."
            );

            return;
        }

        $('#editUserId').val(userId);

        $.ajax({
            url: `/admin/users/${userId}`,
            method: 'GET',

            success: function (response) {

                if (!response.success) {

                    showMsg(
                        "error",
                        "Unable to load user details."
                    );

                    return;
                }

                const user = response.user;

                $('#editUserId').val(user.id);

                $('#editUserName').val(
                    user.name || ''
                );

                $('#editUserEmail').val(
                    user.email || ''
                );

                $('#editUserContact').val(
                    user.contact_number || ''
                );

                $('#editUserAddress').val(
                    user.address || ''
                );

                $('#editUserRole').val(
                    user.role || 'LOCAL'
                );

                $('#editUserStatus').val(
                    user.status || 'ACTIVE'
                );

                if (
                    user.role &&
                    user.role.toUpperCase() === 'ADMIN'
                ) {

                    $('#adminPasswordGroup').show();

                } else {

                    $('#adminPasswordGroup').hide();

                    $('#editUserPassword').val('');
                }


                $('#userDetailsModal').fadeIn(200);
            },

            error: function (xhr) {

                console.error(
                    "Get user error:",
                    xhr.responseJSON
                );

                showMsg(
                    "error",
                    "Failed to load user information."
                );
            }
        });

    });

    $('#closeUserDetailsModal, #cancelUserDetails').on(
        'click',
        function () {

            $('#userDetailsModal').fadeOut(200);

        }
    );


    $('#userDetailsForm').on('submit', function (e) {

        e.preventDefault();

        const userId = $('#editUserId').val();

        if (!userId) {
            showMsg(
                "error",
                "User ID is missing."
            );

            return;
        }

        $.ajax({

            url: `/admin/users/${userId}`,

            method: 'PUT',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),

                name: $('#editUserName').val(),
                email: $('#editUserEmail').val(),
                contact_number: $('#editUserContact').val(),
                address: $('#editUserAddress').val(),
                role: $('#editUserRole').val(),
                status: $('#editUserStatus').val(),

                password: $('#editUserPassword').val()
            },

            success: function (response) {

                if (!response.success) {

                    showMsg(
                        "error",
                        "Unable to update user."
                    );

                    return;
                }

                showMsg(
                    "success",
                    response.message
                );

                $('#userDetailsModal').fadeOut(200);

                $('.content-area').load(
                    '/admin/users'
                );

            },

            error: function (xhr) {

                console.error(
                    "Update user error:",
                    xhr.responseJSON
                );

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    const errors =
                        xhr.responseJSON.errors;

                    const firstError =
                        Object.values(errors)[0][0];

                    showMsg(
                        "error",
                        firstError
                    );

                    return;
                }

                showMsg(
                    "error",
                    "Failed to update user."
                );
            }

        });

    });

});

$(document).on(
    'change',
    '#editUserRole',
    function () {

        const role = $(this).val();

        if (
            role &&
            role.toUpperCase() === 'ADMIN'
        ) {

            $('#adminPasswordGroup').slideDown(150);

        } else {

            $('#adminPasswordGroup').slideUp(150);

            $('#editUserPassword').val('');

            $('#showUserPassword')
                .prop('checked', false);

            $('#editUserPassword')
                .attr('type', 'password');
        }

    }
);


$(document).on('change', '#showUserPassword', function () {

    const passwordInput = $('#editUserPassword');

    if ($(this).is(':checked')) {
        passwordInput.attr('type', 'text');
    } else {
        passwordInput.attr('type', 'password');
    }

});


$(document).on('input', '#userSearch', function () {

    const searchValue = $(this).val().toLowerCase().trim();

    $('#usersTable tbody tr').each(function () {

        const rowText = $(this).text().toLowerCase();

        if (rowText.includes(searchValue)) {
            $(this).show();
        } else {
            $(this).hide();
        }

    });

});

$(document).on('click', '#add-new-user-btn', function () {

    $('#addUserForm')[0].reset();

    $('#addUserModal').fadeIn(200);

});

$(document).on(
    'click',
    '#closeAddUserModal, #cancelAddUser',
    function () {

        $('#addUserModal').fadeOut(200);

    }
);

$(document).on(
    'change',
    '#showAddUserPassword',
    function () {

        if ($(this).is(':checked')) {

            $('#addUserPassword')
                .attr('type', 'text');

        } else {

            $('#addUserPassword')
                .attr('type', 'password');

        }

    }
);

$(document).on(
    'submit',
    '#addUserForm',
    function (e) {

        e.preventDefault();

        $.ajax({

            url: '/admin/users',

            method: 'POST',

            data: {

                _token: $('meta[name="csrf-token"]').attr('content'),

                name: $('#addUserName').val(),

                email: $('#addUserEmail').val(),

                contact_number:
                    $('#addUserContact').val(),

                address:
                    $('#addUserAddress').val(),

                role:
                    $('#addUserRole').val(),

                status:
                    $('#addUserStatus').val(),

                password:
                    $('#addUserPassword').val()

            },

            success: function (response) {

                if (!response.success) {

                    showMsg(
                        "error",
                        "Unable to add user."
                    );

                    return;
                }

                showMsg(
                    "success",
                    response.message
                );

                $('#addUserModal')
                    .fadeOut(200);


                $('.content-area').load(
                    '/admin/users'
                );

            },

            error: function (xhr) {

                console.error(
                    "Add user error:",
                    xhr.responseJSON
                );

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    const errors =
                        xhr.responseJSON.errors;

                    const firstError =
                        Object.values(errors)[0][0];

                    showMsg(
                        "error",
                        firstError
                    );

                    return;
                }

                showMsg(
                    "error",
                    "Failed to add user."
                );

            }

        });

    }
);