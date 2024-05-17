<x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report Form</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-semibold mb-6" style="text-align: center">Weekly Report Form ( {{$weekStartDate}} - {{$weekEndDate}} )</h1>
        <form>
            <div class="mb-4">
                <label for="summary" class="block text-gray-700 font-bold mb-2">Summary:</label>
                <textarea id="summary" name="summary" placeholder="Enter summary of the week's activities, achievements, challenges, and goals achieved" rows="4" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <div class="mb-4">
                <label for="tasks_completed" class="block text-gray-700 font-bold mb-2">Tasks Completed:</label>
                <textarea id="tasks_completed" name="tasks_completed" placeholder="Enter tasks completed during the week" rows="4" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <div class="mb-4">
                <label for="upcoming_tasks" class="block text-gray-700 font-bold mb-2">Upcoming Tasks:</label>
                <textarea id="upcoming_tasks" name="upcoming_tasks" placeholder="Enter upcoming tasks for the next week" rows="4" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <div class="mb-4">
                <label for="issues" class="block text-gray-700 font-bold mb-2">Issues/Challenges:</label>
                <textarea id="issues" name="issues" placeholder="Enter any issues or challenges encountered during the week" rows="4" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Submit</button>
        </form>
    </div>
</body>
</html>

          
</x-app-layout>