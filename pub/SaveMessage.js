function saveMessage()
{
    let message = $('#message').val();
    $.ajax({
        url:"/updateMessage",
        method:"POST",
        data:{message: message},
        success:function (data) {
            $('#message').val('');
            $('#chats').html(data);
        }
    })
}

$("#send").click(function () {
    saveMessage();
});