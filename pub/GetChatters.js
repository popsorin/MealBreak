/**
 * written by Pop Sorin
 */
var nameChatter;
var idChatter;

$(document).on('click', '.start_chat', function () {
    fetch_user();
    make_chat_dialog_box(idChatter, nameChatter);
    $("#user_dialog_" + idChatter).dialog({
        autoOpen: false,
        width: 400
    });
    $('#user_dialog_' + idChatter).dialog('open');
});

$(document).ready(function () {
    fetch_user();

    //when the button to start the chat is pressed this function
    //make a post request to the server and returns the id of the partner

    //every 5 seconds this function makes a call to the server to return the latest
    /*chat messages
    setInterval(function () {
        fetch_user();
        getLatestChatData()
    }, 5000);*/
});

//takes the values of the logged user adn gives them to the idChatter and nameChatter
function fetch_user()
{
    $.ajax({
        url:"http://mealbreak.local/chatters",
        method:"POST",
        success:function (data) {
            var names = JSON.parse(data);
            idChatter = names.id;
            nameChatter = names.name;
        }
    })
}

//this function creates the chatbox for the user read in fetch_user function
function make_chat_dialog_box(to_user_id, to_user_name)
{
    var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have a chat with '+to_user_name+'">';
    modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
    modal_content += '</div>';
    modal_content += '<div class="form-group">';
    modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
    modal_content += '</div><div class="form-group" align="right">';
    modal_content+= '<button type="button" name="send_chat" id="send_chat" class="btn btn-info send_chat">Send</button></div></div>';
    $('#user_model_details').html(modal_content);
}

$(document).on('click', '.send_chat', function () {
    getChatData();
});

//this function posts to the server the id of the person who is logged in,it's message and name
function getChatData()
{
    var chat_message = $('#chat_message_'+ idChatter).val();
    $.ajax({
        url:"/updateMessage",
        method:"POST",
        data:{to_user_id:idChatter, chat_message:chat_message,to_user_name:nameChatter},
        success:function (data) {
            $('#chat_message_'+ idChatter).val('');
            $('#chat_history_'+ idChatter).html(data);
        },
        error:function () {
            alert("ERROR");
        }
    })
}


