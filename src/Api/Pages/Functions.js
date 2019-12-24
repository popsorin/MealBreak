/**
 * Written by Popa Alexandru
 */
function Hello(){
    alert("hello");
}
function display_er(type) {
    var data = document.getElementById(type).value;
    $.ajax
    ({
        type: 'post',
        url: '/var/www/html/my_project/mealbreak/src/Ajax/register-validation.php',
        data: {
            type: type,
            data: data
        },
        success: function (response) {
            if(response ==="    "){
                $("#"+type+"-error").addClass("d-none");
            }
            else {
                $("#"+type+"-error").removeClass("d-none");
                document.getElementById(type+"-message").innerHTML = response;
            }

        }
    });
}