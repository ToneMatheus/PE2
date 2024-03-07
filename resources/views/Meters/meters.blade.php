<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <table border="1px">
        <tr>
            <td>ID</td>
            <td>EAN</td>
            <td>Type</td>
            <td>Installation Date</td>
            <td>Status</td>
        </tr>
        @foreach ($data as $data)
            <tr>
                <td>{{$data->ID}}</td>
                <td>{{$data->EAN}}</td>
                <td>{{$data->type}}</td>
                <td>{{$data->installationDate}}</td>
                <td>{{$data->status}}</td>
            </tr>    
        @endforeach
    </table>

</body>
</html>