<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/all_meters_dashboard.css" rel="stylesheet" type="text/css"/>
    <title>Document</title>
</head>
<body>
    <nav>
        <p class="companyName">Thomas More Energy Company</p>
    </nav>
    <h1>All meters</h1>
    <table class="scheduleTable">
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Address</th>
            <th>Assigned to</th>
            <th>Change assignment to</th>
        </tr>

        @foreach($results as $result)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $result->first_name.' '.$result->last_name }}</td>
                <td>{{ $result->street.' '.$result->number.', '.$result->city  }}</td>
                <td>{{ $result->assigned_to }}</td>
                <td>
                    <select>
                        @foreach($employees as $employee)
                            <option value={{$employee->first_name}} {{$result->assigned_to == $employee->first_name ? 'selected' : ''}}>{{ $employee->first_name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>