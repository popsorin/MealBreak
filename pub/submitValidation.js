function submitValidation()
{
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var password_repeat = document.getElementById("password_repeat").value;
    $.ajax({
        type: 'post',
        url: '/try_to_add',
        data: {
            name:name,
            email:email,
            password:password,
            password_repeat:password_repeat
        },
        success: function (response) {
            if (response === "") {
                window.location.href = "/";
            } else {
                var data = response.split("/");
                document.getElementById(data[1]+"-error").style.display = 'block';
                document.getElementById(data[1]+"-message").innerHTML = data[0];
            }
        }
    });
    return false;

}
