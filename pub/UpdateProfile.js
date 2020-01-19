function updateValues()
{
    var name = $('#name').val();
    var description = $('#desc').val();
    var age = $('#age').val();
    $.ajax({
        url:"/updateProfile",
        type:'post',
        data:{name:name,description:description,age:age},
        success:function () {
            $('#name').val('');
            $('#desc').val('');
            $('#age').val('');
        }
    });
}

function updateProfile()
{
    $.ajax({
        url:"/chatters",
        method:"POST",
        success:function (data) {
            var names = JSON.parse(data);
            idChatter = names.id;
            nameChatter = names.name;
            let descriptionChatter = names.description;
            let emailChatter = names.email;
            let ageChatter = names.age;
            $('#nickname').data('value',idChatter);
            $('#nickname').text(nameChatter);
            $('#email').text(emailChatter);
            $('#years').text(ageChatter);
            $('#description').text(descriptionChatter);
        }
    })
}

$("#save_changes").click(function () {
    updateValues();
});

$("#your_profile").click(function () {
    updateProfile();
});