<div id="chatContainer" class="fixed bottom-0 right-0 w-1/4 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-md max-h-1/3-screen">
    <div id="toggleChat" class="cursor-pointer text-gray-700 dark:text-gray-100 bg-gray-200 dark:bg-gray-700 p-2 text-center">Chat</div>
    <div id="chat" class="hidden p-2 h-128 overflow-y-scroll text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 max-h-96">
    </div>
    <div class="flex justify-start">
        <div id="Responses" class="hidden p-2">
            <button class="presetResponse border-none px-5 py-2 text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" data-message="I have a question">Question</button>
            <button class="presetResponse border-none px-5 py-2 text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" data-message="I have a problem">Problem</button>
        </div>
        <button id="resetChat" class="hidden border-none px-5 py-2 text-gray-700 dark:text-white bg-red-200 dark:bg-red-700 cursor-pointer hover:bg-red-100 dark:hover:bg-red-600">Reset</button>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Decision tree
    var decisionTree = {
        "I have a question": {
            message: "What would you like to inquire about regarding invoices?",
            responses: ["Invoice details", "Payment status", "Other invoice questions"],
            followUp: {
                "Invoice details": {
                    message: "What specific details of the invoices are you interested in?",
                    responses: ["Invoice dates", "Amount due", "Other details"],
                    followUp: {
                        "Invoice dates": {
                            message: "Invoice dates are typically generated at the end of the billing cycle. Do you need assistance with a specific invoice date?"
                        },
                        "Amount due": {
                            message: "The amount due is calculated based on your usage and any outstanding balance. Is there anything specific you'd like to know about the amount due?"
                        },
                        "Other details": {
                            message: "Please specify which details you'd like to inquire about."
                        }
                    }
                },
                "Payment status": {
                    message: "What information do you need regarding your payment status?",
                    responses: ["Payment confirmation", "Outstanding balance", "Other payment inquiries"],
                    followUp: {
                        "Payment confirmation": {
                            message: "We can assist you with confirming your payment status. Please provide any relevant payment details."
                        },
                        "Outstanding balance": {
                            message: "If you have an outstanding balance, we can provide details on how to settle it. Would you like assistance with that?"
                        },
                        "Other payment inquiries": {
                            message: "Please specify your payment-related question."
                        }
                    }
                },
                "Other invoice questions": {
                    message: "Please specify your question about invoices."
                }
            }
        },
        "I have a problem": {
            message: "What issue are you experiencing with invoices?",
            responses: ["Missing invoice", "Incorrect billing", "Other invoice problems"],
            followUp: {
                "Missing invoice": {
                    message: "We apologize for any inconvenience. Please provide details, and we'll assist you in locating the missing invoice."
                },
                "Incorrect billing": {
                    message: "If you believe there's an error in the billing, please describe the issue, and we'll investigate it for you."
                },
                "Other invoice problems": {
                    message: "Please specify your issue with invoices."
                }
            }
        }
    };
    
    // Initial message from the bot
    $('#chat').append('<p>Invoice support: Hello! How can I assist you today?</p>');

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
