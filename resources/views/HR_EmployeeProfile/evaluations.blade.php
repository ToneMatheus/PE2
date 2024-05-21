<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manager Ticket Overview</title>
    </head>
    <x-app-layout>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">First Name</th>
                    <th scope="col" class="px-6 py-3">Last Name</th>
                    <th scope="col" class="px-6 py-3">Open Tickets</th>
                    <th scope="col" class="px-6 py-3">Closed Tickets</th>
                    <th scope="col" class="px-6 py-3">Unresolved Tickets</th>
                    <th scope="col" class="px-6 py-3">Average Closing Time</th>
                    <th scope="col" class="px-6 py-3">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teamMembers as $teamMember)
                    <tr class="bg-gray-600">
                        <td class="p-2">{{ $teamMember->first_name }}</td>
                        <td class="p-2">{{ $teamMember->last_name }}</td>
                        <td class="p-2">{{ $teamMember->tickets[0]['count'] ?? 0 }}</td>
                        <td class="p-2">{{ $teamMember->tickets[1]['count'] ?? 0 }}</td>
                        <td class="p-2">{{ $teamMember->tickets[1]['count'] ?? 0 }}</td>
                        <td class="p-2">{{ $averageClosingTime }} hours</td>
                        <td class="p-2">{{ number_format($teamMember->score, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-app-layout>
</html>
