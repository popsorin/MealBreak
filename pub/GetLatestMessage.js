$(document).ready(function () {

    //when the button to start the chat is pressed this function
    //make a post request to the server and returns the id of the partner

    //every 5 seconds this function makes a call to the server to return the latest
    //chat messages
    setInterval(function () {
        getLatestChatData()
    }, 5000);
});

//gets the latest chat messages
function getLatestChatData()
{
    $.ajax({
        url:"/getLatestMessage",
        method:"POST",
        success:function (data) {
            $('#chats').html(data);
        },
        error:function () {
            alert("ERROR");
        }
    })
}