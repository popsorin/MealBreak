var name = $('#email').text();
function logOut()
{
    $.ajax({
        url: "http://mealbreak.local/logout",
        method: "POST",
        data: {name: name}
    })
}

$("#logout").click(function () {
    logOut();
});