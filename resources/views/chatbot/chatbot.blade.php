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
            message: "What do you have a question about?",
            responses: ["Invoices ", "Contracts ", "Consumption"],
            followUp: {
                "Invoices ": {
                    message: "What would you like to ask regarding Invoices?",
                    responses: ["Invoice dates", "Invoice amounts", "Other invoice questions"],
                    followUp: {
                        "Invoice dates": {
                            message: "Invoice dates are usually set at the beginning of the contract. Do you have a specific question about your invoice dates?"
                        },
                        "Invoice amounts": {
                            message: "Invoice amounts depend on your consumption and the rates in your contract. Do you have a specific question about your invoice amounts?"
                        },
                        "Other invoice questions": {
                            message: "Please specify your question about invoices."
                        }
                    }
                },
                "Contracts ": {
                    message: "What would you like to ask regarding Contracts?",
                    responses: ["Contract duration", "Contract terms", "Other contract questions"],
                    followUp: {
                        "Contract duration": {
                            message: "Contract durations are typically for a year or more. Do you have a specific question about your contract duration?"
                        },
                        "Contract terms": {
                            message: "Contract terms include payment terms, service level agreements, etc. Do you have a specific question about your contract terms?"
                        },
                        "Other contract questions": {
                            message: "Please specify your question about contracts."
                        }
                    }
                },
                "Consumption": {
                    message: "What would you like to ask regarding Consumption?",
                    responses: ["Consumption data", "Consumption rates", "Other consumption questions"],
                    followUp: {
                        "Consumption data": {
                            message: "Consumption data is usually provided in your monthly bill. Do you have a specific question about your consumption data?"
                        },
                        "Consumption rates": {
                            message: "Consumption rates depend on your contract and the time of use. Do you have a specific question about your consumption rates?"
                        },
                        "Other consumption questions": {
                            message: "Please specify your question about consumption."
                        }
                    }
                },
            },
        },
        "I have a problem": {
            message: "What do you have a problem with?",
            responses: ["Invoices", "Contracts", "meters"],
            followUp: {
                "Invoices": {
                    message: "What problem are you experiencing with Invoices?",
                    responses: ["Invoice not received", "Incorrect invoice amount", "Other invoice problems"],
                    followUp: {
                        "Invoice not received": {
                            message: "I'm sorry to hear that you're having a problem with 'Invoice not received'. Please <a href=\"{{ route('create-ticket') }}?message=Invoice not received\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "Incorrect invoice amount": {
                            message: "I'm sorry to hear that you're having a problem with 'Incorrect invoice amount'. Please <a href=\"{{ route('create-ticket') }}?message=Incorrect invoice amount\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "Other invoice problems": {
                            message: "I'm sorry to hear that you're having a problem with 'Other invoice problems'. Please <a href=\"{{ route('create-ticket') }}?message=Other invoice problems\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        }
                    }
                },
                "Contracts": {
                    message: "What problem are you experiencing with Contracts?",
                    responses: ["Contract not received", "Incorrect contract terms", "Other contract problems"],
                    followUp: {
                        "Contract not received": {
                            message: "I'm sorry to hear that you're having a problem with 'Contract not received'. Please <a href=\"{{ route('create-ticket') }}?message=Contract not received\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "Incorrect contract terms": {
                            message: "I'm sorry to hear that you're having a problem with 'Incorrect contract terms'. Please <a href=\"{{ route('create-ticket') }}?message=Incorrect contract terms\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "Other contract problems": {
                            message: "I'm sorry to hear that you're having a problem with 'Other contract problems'. Please <a href=\"{{ route('create-ticket') }}?message=Other contract problems\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        }
                    }
                },
                "meters": {
                    message: "What problem are you experiencing with meters?",
                    responses: ["broken counter", "wrong readings", "Other meter problems"],
                    followUp: {
                        "broken counter": {
                            message: "I'm sorry to hear that you're having a problem with 'broken counter'. Please <a href=\"{{ route('create-ticket') }}?message=broken counter\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "wrong readings": {
                            message: "I'm sorry to hear that you're having a problem with 'wrong readings'. Please <a href=\"{{ route('create-ticket') }}?message=wrong readings\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        },
                        "Other meter problems": {
                            message: "I'm sorry to hear that you're having a problem with 'Other meter problems'. Please <a href=\"{{ route('create-ticket') }}?message=Other meter problems\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to go to our ticketing page and fill in the description of your problem. We'll help you as soon as possible."
                        }
                    }
                },
            }
        }
    };
    
    // Initial message from the bot
    $('#chat').append('<p>Customer support: Hello! How can I assist you today?</p>');

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
            $('#chat').append('<p>Customer support: ' + decision.message + '</p>');
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