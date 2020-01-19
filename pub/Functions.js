function display_er(type)
{
    var data = document.getElementById(type).value;
    var password = document.getElementById("password").value;
    $.ajax({
        type: 'post',
        url: '/Ajax_request',
        data: {
            type: type,
            data: data,
            password: password
        },
        success: function (response) {
            if (response ==="") {
                //$("#"+type+"-error").addClass("d-none");
                document.getElementById(type+"-error").style.display = 'none';
            } else {
                //$("#"+type+"-error").removeClass("d-none");
                document.getElementById(type+"-error").style.display = 'block';
                document.getElementById(type+"-message").innerHTML = response;
            }

        }
    });
}