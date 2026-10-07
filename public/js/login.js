$(document).on('change', '#showPassword', function () {

    if ($(this).is(':checked')) {

        $('#password').attr('type', 'text');

    } else {

        $('#password').attr('type', 'password');

    }

});

 $(document).ready(function () {
    $('#showPassword').on('change', function () {
        if ($(this).is(':checked')) {
            $('#password').attr('type', 'text');
            $('#password_confirmation')
                .attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
            $('#password_confirmation')
                .attr('type', 'password');
        }
    });
});
