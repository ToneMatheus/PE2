<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/jobApply.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Job application</title>
</head>

<body style="background-color: #a2bce0;">
    <div class="containe c">
        <h2 style="text-align: center">Job application</h2>
        <form action="#">
            <div class="form-group">
                <label for="fname">First name:</label>
                <input type="text" class="form-control" id="fname">
            </div>
            <div class="form-group">
                <label for="lname">Last name:</label>
                <input type="text" class="form-control" id="lname">
            </div>
            <div class="form-group">
              <label for="email">Email address:</label>
              <input type="email" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="proflie">Your profile:</label>
                <textarea class="form-control" id="profile" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
    </div>
</body>
