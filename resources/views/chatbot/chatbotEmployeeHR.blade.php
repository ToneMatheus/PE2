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
            message: "What HR-related topic would you like to inquire about?",
            responses: ["Benefits", "Employee policies", "Training and development"],
            followUp: {
                "Benefits": {
                    message: "What specific aspect of benefits do you want to know about?",
                    responses: ["Health insurance", "Retirement plans", "Other benefits questions"],
                    followUp: {
                        "Health insurance": {
                            message: "Our health insurance options include various plans with different coverage. What would you like to know about health insurance?"
                        },
                        "Retirement plans": {
                            message: "We offer retirement plans such as 401(k) with matching contributions. Do you have any specific questions about our retirement plans?"
                        },
                        "Other benefits questions": {
                            message: "Please specify your question about benefits."
                        }
                    }
                },
                "Employee policies": {
                    message: "What employee policy are you interested in?",
                    responses: ["Attendance policy", "Code of conduct", "Other policy questions"],
                    followUp: {
                        "Attendance policy": {
                            message: "Our attendance policy outlines expectations regarding attendance and leave. Do you have any specific questions about it?"
                        },
                        "Code of conduct": {
                            message: "Our code of conduct sets standards for ethical behavior. What would you like to know about our code of conduct?"
                        },
                        "Other policy questions": {
                            message: "Please specify your question about employee policies."
                        }
                    }
                },
                "Training and development": {
                    message: "What aspect of training and development interests you?",
                    responses: ["Training programs", "Career development opportunities", "Other training questions"],
                    followUp: {
                        "Training programs": {
                            message: "We offer various training programs to enhance skills and knowledge. Do you have any specific questions about our training programs?"
                        },
                        "Career development opportunities": {
                            message: "We provide opportunities for career growth and advancement. What would you like to know about our career development programs?"
                        },
                        "Other training questions": {
                            message: "Please specify your question about training and development."
                        }
                    }
                }
            }
        },
        "I have a problem": {
            message: "What HR-related issue are you experiencing?",
            responses: ["Payroll", "Performance evaluation", "Workplace harassment"],
            followUp: {
                "Payroll": {
                    message: "What problem are you facing with payroll?",
                    responses: ["Incorrect paycheck", "Payroll deductions", "Other payroll problems"],
                    followUp: {
                        "Incorrect paycheck": {
                            message: "I'm sorry to hear that you're experiencing issues with your paycheck. Please <a href=\"#\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to contact our payroll department for assistance."
                        },
                        "Payroll deductions": {
                            message: "Our payroll deductions include taxes and benefits contributions. Do you have any questions or concerns about payroll deductions?"
                        },
                        "Other payroll problems": {
                            message: "Please specify your problem with payroll."
                        }
                    }
                },
                "Performance evaluation": {
                    message: "What issue are you facing with performance evaluation?",
                    responses: ["Unclear evaluation criteria", "Feedback process", "Other evaluation problems"],
                    followUp: {
                        "Unclear evaluation criteria": {
                            message: "Our performance evaluation criteria are designed to be transparent. What aspect of the criteria is unclear to you?"
                        },
                        "Feedback process": {
                            message: "Feedback is crucial for improvement. Do you have any concerns about the feedback process?"
                        },
                        "Other evaluation problems": {
                            message: "Please specify your issue with performance evaluation."
                        }
                    }
                },
                "Workplace harassment": {
                    message: "What problem are you experiencing with workplace harassment?",
                    responses: ["Incident reporting", "Conflict resolution", "Other harassment issues"],
                    followUp: {
                        "Incident reporting": {
                            message: "We take workplace harassment seriously. Please <a href=\"#\" class=\"text-indigo-600 hover:text-indigo-900\">click here</a> to report any incidents anonymously."
                        },
                        "Conflict resolution": {
                            message: "Resolving conflicts promptly is important. Do you need assistance with a specific conflict?"
                        },
                        "Other harassment issues": {
                            message: "Please specify your problem with workplace harassment."
                        }
                    }
                }
            }
        }
    };
    
    // Initial message from the bot
    $('#chat').append('<p>HR support: Hello! How can I assist you today?</p>');

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
            $('#chat').append('<p>HR support: ' + decision.message + '</p>');
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
