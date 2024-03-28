<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Invoice Extra Form</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- If you want to use your custom CSS file, you can include it here -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <div class="max-w-3xl mx-auto bg-white rounded-md shadow-md overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold mb-6">Add Invoice Extra Form</h1>

                <form id="addInvoiceExtraForm" action="{{ route('addInvoiceExtraForm')}}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="type" class="block text-lg font-medium text-gray-700 mb-2">Type:</label>
                        <input type="text" id="type" name="type"
                            class="py-2 px-4 bg-gray-200 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-lg border-gray-300 rounded-md">
                    </div>
                    <div class="mb-6">
                        <label for="amount" class="block text-lg font-medium text-gray-700 mb-2">Amount:</label>
                        <input type="number" id="amount" name="amount"
                            class="py-2 px-4 bg-gray-200 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-lg border-gray-300 rounded-md">
                    </div>
                    <div class="mb-6">
                        <label for="userID" class="block text-lg font-medium text-gray-700 mb-2">User ID:</label>
                        <input readOnly type="number" id="userID" name="userID" value="{{ $userID }}"
                            class="py-2 px-4 bg-gray-200 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-lg border-gray-300 rounded-md">
                    </div>
                    <button type="submit"
                        class="w-full py-3 px-6 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700 text-lg">Add
                        extra invoice line</button>
                </form>
                
                <!-- Back button -->
                <button onclick="history.back()"
                    class="block mt-4 py-3 px-6 bg-gray-300 text-gray-700 hover:bg-gray-400 hover:text-gray-800 font-medium text-lg rounded-md focus:outline-none focus:bg-gray-400 focus:text-gray-800">
                    Back
                </button>
            </div>
        </div>
    </div>
</body>