var name = $('#email').text();
function logOut()
{
    $.ajax({
        url: "/logout",
        method: "POST",
        data: {name: name}
    })
}

$("#logout").click(function () {
    logOut();
});