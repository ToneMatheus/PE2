<div id="chatContainer" class="fixed bottom-0 right-0 w-1/4 border border-gray-700 bg-gray-800 shadow-md max-h-1/3-screen">
    <div id="toggleChat" class="cursor-pointer text-gray-100 bg-gray-700 p-2 text-center">Chat</div>
    <div id="chat" class="hidden p-2 h-128 overflow-y-scroll text-gray-300 bg-gray-700 max-h-96">
    </div>
    <div id="Responses" class="hidden p-2">
        <button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="I have a question">Question</button>
        <button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="I have a problem">Problem</button>
    </div>
    <button id="resetChat" class="hidden border-none px-5 py-2 text-white bg-red-700 cursor-pointer hover:bg-red-600">Reset</button>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Decision tree
    var decisionTree = {
        "I have a question": {
            message: "What do you have a question about?",
            responses: ["Invoices", "Contracts", "Consumption", "Contract information"]
        },
        "I have a problem": {
            message: "What do you have a problem with?",
            responses: ["Invoices", "Contracts"]
        }
    };

    // Initial message from the bot
    $('#chat').append('<p>Bot: Hello! How can I assist you today?</p>');

    // Toggle chat window
    $('#toggleChat').click(function() {
        $('#chat, #Responses, #resetChat').slideToggle();
    });

    $('#Responses').on('click', '.presetResponse', function() {
        var userMessage = $(this).data('message');

        // Update chat
        $('#chat').append('<p>You: ' + userMessage + '</p>');
        $('#chat').append('<p>Bot: ' + decisionTree[userMessage].message + '</p>');
        $('#chat').scrollTop($('#chat')[0].scrollHeight);

        // Update responses
        $('#Responses').empty();
        $.each(decisionTree[userMessage].responses, function(index, response) {
            $('#Responses').append('<button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="' + response + '">' + response + '</button>');
        });
    });

    // Reset chat
    $('#resetChat').click(function() {
        $('#chat').empty().append('<p>Bot: Hello! How can I assist you today?</p>');
        $('#Responses').empty()
            .append('<button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="I have a question">Question</button>')
            .append('<button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="I have a problem">Problem</button>');
    });
});
</script>