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
            message: "What can I assist you with today?",
            responses: ["Account inquiries", "Service requests", "Billing questions"],
            followUp: {
                "Account inquiries": {
                    message: "What specific information do you need about your account?",
                    responses: ["Account balance", "Payment history", "Other account inquiries"],
                    followUp: {
                        "Account balance": {
                            message: "Your current account balance is important. Do you need details about your balance?"
                        },
                        "Payment history": {
                            message: "Your payment history contains valuable information. Would you like to know more about it?"
                        },
                        "Other account inquiries": {
                            message: "Please specify your account-related question."
                        }
                    }
                },
                "Service requests": {
                    message: "What service do you require assistance with?",
                    responses: ["Technical support", "Account changes", "Other service requests"],
                    followUp: {
                        "Technical support": {
                            message: "Technical issues can be frustrating. How can I assist you with technical support?"
                        },
                        "Account changes": {
                            message: "Account changes may include updates to personal information or service plans. What changes do you need?"
                        },
                        "Other service requests": {
                            message: "Please specify your service request."
                        }
                    }
                },
                "Billing questions": {
                    message: "What billing information do you need?",
                    responses: ["Invoice inquiries", "Payment options", "Other billing questions"],
                    followUp: {
                        "Invoice inquiries": {
                            message: "Understanding your invoices is important. What specific questions do you have?"
                        },
                        "Payment options": {
                            message: "We offer various payment options for your convenience. Do you need assistance with payments?"
                        },
                        "Other billing questions": {
                            message: "Please specify your billing-related question."
                        }
                    }
                }
            }
        },
        "I have a problem": {
            message: "What issue are you experiencing?",
            responses: ["Technical issues", "Account problems", "Billing discrepancies"],
            followUp: {
                "Technical issues": {
                    message: "Technical problems can be frustrating. Please describe the issue, and we'll assist you."
                },
                "Account problems": {
                    message: "Account-related issues can be resolved. Please provide details of the problem."
                },
                "Billing discrepancies": {
                    message: "Billing discrepancies require attention. Please describe the discrepancy, and we'll investigate it."
                }
            }
        }
    };
    
    // Initial message from the bot
    $('#chat').append('<p>Customer service support: Hello! How can I assist you today?</p>');

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
            $('#chat').append('<p>Customer Service: ' + decision.message + '</p>');
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
