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
            var names = JSON.parse(data);
            let idChatter = names.id;
            let nameChatter = names.name;
            /*
            make_chat_dialog_box(idChatter, nameChatter);
            $("#user_dialog_" + idChatter).dialog({
                autoOpen: false,
                width: 400
            });
            $('#user_dialog_' + idChatter).dialog('open');*/
        }
    });
}

$("#match").click(function () {
    findMatch();
});