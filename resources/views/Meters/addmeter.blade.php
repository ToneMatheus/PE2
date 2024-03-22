

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Meter</title>
</head>
<body>
    <form action="/meters/add" method="POST">
        @csrf
        <label for="type">Type:</label>
        <input type="text" list="types" name="type">
        <datalist id="types">
            <option value="Electricity">Electricity</option>
            <option value="Gas">Gas</option>
        </datalist>
        <label for="installationDate">Installation Date	:</label>
        <input type="date" name="installationDate">
        <label for="status">Status:</label>
        <input type="text" list="statustypes" name="status">
        <datalist id="statustypes">
            <option value="Installed">Installed</option>
            <option value="In Storage">In Storage</option>
        </datalist>
        <button>Add</button>
    </form>
    
</body>
</html>