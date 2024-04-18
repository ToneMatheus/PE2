<div id="chatContainer" class="fixed bottom-0 right-0 w-1/4 border border-gray-300 bg-gray-200 shadow-md">
        <div id="toggleChat" class="cursor-pointer text-gray-800 bg-gray-400 p-2 text-center">Chat</div>
        <div id="chat" class="hidden p-2 h-128 overflow-y-scroll text-gray-800 bg-gray-300">
        </div>
        <form id="chatForm" class="hidden p-2">
            <input type="text" id="userMessage" class="w-4/5 border border-gray-300 p-2 text-gray-800 bg-gray-200" placeholder="Type your message here">
            <button type="submit" class="border-none px-5 py-2 text-white bg-gray-800 cursor-pointer hover:bg-gray-700">Send</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initial message from the bot
        $('#chat').append('<p>Bot: Hello! How can I assist you today?</p>');

        // Only allow alphanumeric input
        $('#userMessage').on('keydown', function(event) {
            var key = event.which || event.keyCode;
            if (key === 32|| key == 8) {
                return true;
            } else {
                var regex = new RegExp("^[a-zA-Z0-9]+$");
                var str = String.fromCharCode(!event.charCode ? key : event.charCode);
                if (regex.test(str)) {
                return true;
            }   
        }

            event.preventDefault();
            return false;
        });

        // Toggle chat window
        $('#toggleChat').click(function() {
            $('#chat, #chatForm').slideToggle();
        });

        $('#chatForm').submit(function(event) {
            event.preventDefault();

            var userMessage = $('#userMessage').val();

            if (userMessage.trim() === '') {
                return false;
            }

            $.ajax({
                url: '/customer/chatbot',
                method: 'POST',
                data: {
                    message: userMessage,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#chat').append('<p>You: ' + userMessage + '</p>');
                    $('#chat').append('<p>Bot: ' + response.message + '</p>');
                    $('#userMessage').val('');
                    $('#chat').scrollTop($('#chat')[0].scrollHeight);
                }
            });
        });
    });
    </script>