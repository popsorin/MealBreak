$(document).ready(function () {
    $.ajax({
        url:"/postMatch",
        method:"POST",
        success:function (data) {
            var information = JSON.parse(data);
            let idChatter = information.id;
            let nameChatter = information.name;
            let ageChatter = information.age;
            let descriptionChatter = information.description;
            let location = information.location;
            let pub = information.pubName;
            $('#pair__name').data('value',idChatter);
            $('#pair__name').text(nameChatter);
            $('#pair__details').text("You have a chat with " + nameChatter);
            $('#pair__age').text(ageChatter);
            $('#pair__description').text(descriptionChatter);
            $('#local__name').text(pub);
            $('#local__address').text(location);
        }
    })
});



