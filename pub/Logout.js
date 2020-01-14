var name = $('#email').text();
function logOut()
{
    $.ajax({
        url: "/logout",
        method: "POST",
        data: {name: name},
        success: function (data) {
            window.location.href = "/login";
        }
    })
}

$("#logout").click(function () {
    logOut();
});