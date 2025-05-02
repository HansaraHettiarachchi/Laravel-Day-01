$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('#logout').click(() => {
        $.ajax({
            type: "GET",
            url: "/distory-session",

            success: function (response) {
                // console.log(response);

                window.location.reload();
            }
        });
    });
});