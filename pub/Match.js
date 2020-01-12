function findMatch()
{
    //var data = document.getElementById(type).value;
    //var password = document.getElementById("password").value;
    $.ajax({
        type: 'post',
        url: '/match',
        data: {
            //type: type,
            //data: data,
            //password: password
        },
        success: function (response) {
            alert(response);
            if(response === "success") {

                //here we have to do the frond end changes
                //like redirect or changes to the document
                //delete some content and add the content for the chat

                //also while the button is clicked we have to erase it and
                //add a substitute message like: you are being match,
                //please wait for about x seconds; something to inform the user
                //that hes request is being processed
            } else {
                //do something else
            }

        }
    });
}

$("#match").click(function () {
    findMatch();
});