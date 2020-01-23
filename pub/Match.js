function findMatch()
{
    $.ajax({
        type: 'post',
        url: '/match',
        data: {
            //type: type,
            //data: data,
            //password: password
        },
        success: function (data) {
            let information = JSON.parse(data);
            let uriChatter = information.uri;
            window.location.href = uriChatter;
        }
    });
}

$("#match").click(function () {
    findMatch();
});