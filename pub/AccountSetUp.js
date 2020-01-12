function fetchUserData()
{
    $.ajax({
        url:"/chatters",
        method:"POST",
        success:function (data) {
            var names = JSON.parse(data);
            idChatter = names.id;
            nameChatter = names.name;
            descriptionChatter = names.description;
            emailChatter = names.email;
            ageChatter = names.age;
            $('#nickname').data('value',idChatter);
            $('#nickname').text(nameChatter);
            $('#email').text(emailChatter);
            $('#years').text(ageChatter);
            $('#description').text(descriptionChatter);
        }
    })
}

$(document).ready(function () {
    fetchUserData();
});
