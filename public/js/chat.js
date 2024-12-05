// var userChat = document.getElementById('userChat').value;
// var model_id = document.getElementById('model_id').value;
// var homeUrl = document.getElementById('homeUrl').value;
// var course_id = document.getElementById('course_id').value;
// var username_teacher = document.getElementById('username_teacher').value;

function autoExpand(textarea) {
    textarea.style.height = "auto";
    textarea.style.height = (textarea.scrollHeight) + "px";
}

function resetTextareaHeight() {
    const userInput = document.getElementById('userInput');
    userInput.style.height = "56px";
}

document.getElementById("userInput").addEventListener("keydown", async function(event) {
    if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault();
        const userMessage = document.getElementById('userInput').value;
        // var model_id = localStorage.getItem('model_id');
        await appendMessage(userMessage);
    }
});

document.getElementById('send-btn').addEventListener('click', async () => {
    const userMessage = userInput.value;
    // var model_id = localStorage.getItem('model_id');
    await appendMessage(userMessage);
});

async function getBotResponseFromChatApiLaravel(userMessage) {
    var filename = $('#send-btn').attr('data-option-value');
    try {
        const response = await $.ajax({
            url: '/api/chat/sendMessage',
            method: 'POST',
            data: {
                userMessage: userMessage,
            }
        });

        // var data = JSON.parse(response);
        // $('#send-btn').attr('data-option-value', data.filename);
        // loadHistoryChat(model_id);
        // if (data.message == 'Fail') {
        //     return data.data.messages.messages;
        // }
        return response.message;
    } catch (error) {
        console.error('Error when loading data:', error);
        throw error;
    }
}

async function appendMessage(message) {
    const userMessage = document.getElementById('userInput').value;
    if (userMessage.trim() === '') return;
    const userMessageDiv = document.createElement('div');
    userMessageDiv.classList.add('user-inbox', 'inbox');
    const wrapDiv = document.createElement('div');
    wrapDiv.classList.add('wrap');
    const iconDiv = document.createElement('div');
    iconDiv.classList.add('icon');
    const img = document.createElement('img');
    img.src = "https://thecodeplayer.com/u/uifaces/20.jpg";
    img.alt = "";
    iconDiv.appendChild(img);
    const msgHeader = document.createElement('div');
    msgHeader.classList.add('msg-header');
    const preUserMessage = document.createElement('pre');
    preUserMessage.textContent = userMessage;
    msgHeader.appendChild(preUserMessage);
    wrapDiv.appendChild(iconDiv);
    wrapDiv.appendChild(msgHeader);
    userMessageDiv.appendChild(wrapDiv);
    document.getElementById('chatMessages').appendChild(userMessageDiv);
    document.getElementById('userInput').value = '';
    resetTextareaHeight();
    const botMessageDiv = document.createElement("div");
    botMessageDiv.classList.add("bot-inbox", "inbox", "waiting");
    botMessageDiv.innerHTML = `
        <div class="wrap">
            <div class="icon">
                <img src="https://thecodeplayer.com/u/uifaces/21.jpg" alt="">
            </div>
            <div class="msg-header">
                <pre>Loading ...</pre>
            </div>
        </div>
    `;
    document.getElementById('chatMessages').appendChild(botMessageDiv);
    document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
    try {
        const botResponse = await getBotResponseFromChatApiLaravel(message);

        const waitingMessage = document.querySelector(".bot-inbox.waiting");
        if (waitingMessage) {
            waitingMessage.remove();
        }
        // const botResponse = await getBotResponseFromChatGPT(message, chatHistory1, type);
        const botMessageDiv = document.createElement('div');
        botMessageDiv.classList.add('bot-inbox', 'inbox');
        const encodedBotResponse = escapeHtml(botResponse);

        botMessageDiv.innerHTML = `
                <div class="wrap">
                    <div class="icon">
                        <img src="https://thecodeplayer.com/u/uifaces/21.jpg" alt="">
                    </div>
                    <div class="msg-header">
                        <pre>${encodedBotResponse}</pre>
                    </div>
                </div>
            `;
        document.getElementById('chatMessages').appendChild(botMessageDiv);

        document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;

    } catch (error) {
        console.error('ChatGPT:', error);
    }

}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getHistoryChatDetail(filename) {
    document.getElementById('chatMessages').innerHTML = '';
    $('#send-btn').removeAttr('data-option-value');
    $("#send-btn").prop("disabled", true);
    $("#userInput").prop("disabled", true);
    console.log('getHistoryChatDetail');
    $.ajax({
        url: '/wp-content/plugins/chat_ptutor/view/action/chat_history_detail.php',
        type: 'POST',
        data: {
            filename: filename,
            userName: userChat,
            course_id: course_id,
            username_teacher: username_teacher
        },
        success: function(response) {
            var data = JSON.parse(response);
            var content = JSON.parse(data.data.content);
            console.log(filename);
            content.forEach(function(item) {
                if (item.role == 'user') {
                    const userMessage = item.content;
                    const text2WithoutBackslash = userMessage.replace(/\\/g, '');
                    if (userMessage.trim() === '') return;
                    const userMessageDiv = document.createElement('div');
                    userMessageDiv.classList.add('user-inbox', 'inbox');
                    const wrapDiv = document.createElement('div');
                    wrapDiv.classList.add('wrap');
                    const iconDiv = document.createElement('div');
                    iconDiv.classList.add('icon');
                    const img = document.createElement('img');
                    img.src = "https://thecodeplayer.com/u/uifaces/20.jpg";
                    img.alt = "";
                    iconDiv.appendChild(img);
                    const msgHeader = document.createElement('div');
                    msgHeader.classList.add('msg-header');
                    const preUserMessage = document.createElement('pre');
                    preUserMessage.textContent = text2WithoutBackslash;
                    msgHeader.appendChild(preUserMessage);
                    wrapDiv.appendChild(iconDiv);
                    wrapDiv.appendChild(msgHeader);
                    userMessageDiv.appendChild(wrapDiv);
                    document.getElementById('chatMessages').appendChild(userMessageDiv);
                }

                // if (item.role == 'assistant') {
                //     const botMessageDiv = document.createElement('div');
                //     const assistantMessage = item.content;
                //     const text2WithoutBackslash = assistantMessage.replace(/\\/g, '');
                //     botMessageDiv.classList.add('bot-inbox', 'inbox');
                //     botMessageDiv.insertAdjacentHTML('beforeend', `
                // <div class="wrap">
                //     <div class="icon">
                //         <img src="https://thecodeplayer.com/u/uifaces/21.jpg" alt="">
                //     </div>
                //     <div class="msg-header">
                //         <pre>${text2WithoutBackslash}</pre>
                //     </div>
                // </div>
                // `);

                //     document.getElementById('chatMessages').appendChild(botMessageDiv);
                // }
                if (item.role == 'assistant') {
                    const botMessageDiv = document.createElement('div');
                    const assistantMessage = item.content;
                    botMessageDiv.classList.add('bot-inbox', 'inbox');
                    const encodedBotResponse = escapeHtml(assistantMessage);
            
                    botMessageDiv.innerHTML = `
                            <div class="wrap">
                                <div class="icon">
                                    <img src="https://thecodeplayer.com/u/uifaces/21.jpg" alt="">
                                </div>
                                <div class="msg-header">
                                    <pre>${encodedBotResponse}</pre>
                                </div>
                            </div>
                        `;
                    document.getElementById('chatMessages').appendChild(botMessageDiv);
                }
            })

            $('#send-btn').attr('data-option-value', filename);
            $("#send-btn").prop("disabled", false);
            $("#userInput").prop("disabled", false);
        },
        error: function(err) {
            console.log(err)
            $("#send-btn").prop("disabled", false);
            $("#userInput").prop("disabled", false);
        }
    });
}

$(document).on('click', '.newChat', function() {
    document.getElementById('chatMessages').innerHTML = '';
    const botMessageDiv = document.createElement("div");
    botMessageDiv.classList.add("bot-inbox", "inbox", "waiting");
    botMessageDiv.innerHTML = `
        <div class="wrap">
            <div class="icon">
                <img src="https://thecodeplayer.com/u/uifaces/21.jpg" alt="">
            </div>
            <div class="msg-header">
                <pre>Hello! How can I help you today?</pre>
            </div>
        </div>
    `;
    document.getElementById('chatMessages').appendChild(botMessageDiv);
    $('#send-btn').removeAttr('data-option-value');
});

function getHistoryChat(model_id) {
    $('.lds-ring-4').show();
    // document.getElementById('chatMessages').innerHTML = '';
    $('#send-btn').removeAttr('data-option-value');
    console.log('getHistoryChat');
    $.ajax({
        url: '/wp-content/plugins/chat_ptutor/view/action/show_model.php',
        method: 'POST',
        data: {
            userName: userChat,
            model_id: model_id,
            course_id: course_id,
            username_teacher: username_teacher
        },
        success: function(response) {
            var data = JSON.parse(response);
            var history = data.data.history_chat_test;
            $('#sidebar-logo').empty();
            var newChatButton = $('<button>').addClass('newChat').text('+ New chat');
            $('#sidebar-logo').append(newChatButton);
            if(data.code == 200){
                history.forEach(function(item) {
                    var button = $('<button>').text(item);
                    button.click(function() {
                        getHistoryChatDetail(item);
                    });
                    $('#sidebar-logo').append(button);
                });
            }
            $('.lds-ring-4').hide();
        },
        error: function() {
            alert('Error when load data');
        }
    });
}

function loadHistoryChat(model_id) {
    $('.lds-ring-4').show();
    $.ajax({
        url: '/wp-content/plugins/chat_ptutor/view/action/show_model.php',
        method: 'POST',
        data: {
            userName: userChat,
            model_id: model_id,
            course_id: course_id,
            username_teacher: username_teacher
        },
        success: function(response) {
            var data = JSON.parse(response);
            var history = data.data.history_chat_test;
            $('#sidebar-logo').empty();
            var newChatButton = $('<button>').addClass('newChat').text('+ New chat');
            $('#sidebar-logo').append(newChatButton);
            if(data.code == 200){
                history.forEach(function(item) {
                    var button = $('<button>').text(item);
                    button.click(function() {
                        getHistoryChatDetail(item);
                    });
                    $('#sidebar-logo').append(button);
                });
            }
            $('.lds-ring-4').hide();
        },
        error: function() {
            alert('Error when load data');
        }
    });
}

// getHistoryChat(model_id);