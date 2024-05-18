<div id="chatContainer" class="fixed bottom-0 right-0 w-1/4 border border-gray-700 bg-gray-800 shadow-md max-h-1/3-screen">
    <div id="toggleChat" class="cursor-pointer text-gray-100 bg-gray-700 p-2 text-center">Chat</div>
    <div id="chat" class="hidden p-2 h-128 overflow-y-scroll text-gray-300 bg-gray-700 max-h-96">
    </div>
    <div class="flex justify-start">
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
            message: "What would you like to inquire about regarding meters?",
            responses: ["Meter installation", "Meter readings", "Other meter questions"],
            followUp: {
                "Meter installation": {
                    message: "Do you need information about the process of meter installation?"
                },
                "Meter readings": {
                    message: "Are you facing any issues with meter readings or understanding them?"
                },
                "Other meter questions": {
                    message: "Please specify your question about meters."
                }
            }
        },
        "I have a problem": {
            message: "What issue are you experiencing with meters?",
            responses: ["Faulty meter", "Incorrect readings", "Other meter problems"],
            followUp: {
                "Faulty meter": {
                    message: "We apologize for the inconvenience. Please provide details of the fault, and we'll assist you."
                },
                "Incorrect readings": {
                    message: "If you believe there's an error in the readings, please describe the issue, and we'll investigate it for you."
                },
                "Other meter problems": {
                    message: "Please specify your issue with meters."
                }
            }
        }
    };
    
    // Initial message from the bot
    $('#chat').append('<p>Meter support: Hello! How can I assist you today?</p>');

    // Toggle chat window
    $('#toggleChat').click(function() {
        $('#chat, #Responses, #resetChat').slideToggle();
    });

    function findDecision(userMessage, tree) {
        var decision = null;

        $.each(tree, function(question, details) {
            if (question === userMessage) {
                decision = details;
            } else if ('followUp' in details) {
                var result = findDecision(userMessage, details.followUp);
                if (result !== null) {
                    decision = result;
                }
            }
        });

        return decision;
    }

    $('#Responses').on('click', '.presetResponse', function() {
        var userMessage = $(this).data('message');
        
        // Update chat
        $('#chat').append('<p>You: ' + userMessage + '</p>');

        var decision = findDecision(userMessage, decisionTree);
        
        if (decision !== null) {
            $('#chat').append('<p>Support: ' + decision.message + '</p>');
            $('#chat').scrollTop($('#chat')[0].scrollHeight);
        
            // Update responses
            $('#Responses').empty();
            $.each(decision.responses, function(index, response) {
                $('#Responses').append('<button class="presetResponse border-none px-5 py-2 text-white bg-gray-700 cursor-pointer hover:bg-gray-600" data-message="' + response + '">' + response + '</button>');
            });
        }
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
