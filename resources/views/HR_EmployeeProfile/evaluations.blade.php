<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manager Ticket Overview</title>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Open Tickets</th>
                    <th>Closed Tickets</th>
                    <th>Unresolved Tickets</th>
                    <th>Average Closing Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teamMembers as $teamMember)
                    <tr class="bg-gray-600">
                        <td class="p-2">{{ $teamMember->first_name }}</td>
                        <td class="p-2">{{ $teamMember->last_name }}</td>
                        <td class="p-2">{{ $teamMember->tickets[0] ?? 0 }}</td>
                        <td class="p-2">{{ $teamMember->tickets[1] ?? 0 }}</td>
                        <td class="p-2">{{ $teamMember->tickets[1] ?? 0 }}</td>
                        <td class="p-2">{{ $averageClosingTime }} hours</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
