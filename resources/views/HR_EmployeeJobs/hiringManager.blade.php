<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="/css/hiringManager.css" rel="stylesheet" type="text/css"/>
        <link href="/css/nav.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Hiring manager page</title>

        <script>
          function check()
          {
              var title = document.getElementById('title').value;
              var desc = document.getElementById('desc').value;
  
              if(title == "" || desc == "")
              {
                  if(title == "")
                  {
                      document.getElementById('title').placeholder = 'This field in required!'
                  }
  
                  if(desc == "")
                  {
                      document.getElementById('desc').placeholder = "This field is required!"
                  }
                  
              return false;
              }
  
              return true;
          }
      </script>
    </head>
    <body style="background-color: #a2bce0;">
        <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Energy supplier</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('jobs')}}">Jobs</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Hiring manager</a>
                  </li>
                </ul>
              </div>
            </div>
        </nav>

        <h1 style="text-align: center; margin: 80px 0px; "><u>Hiring manager</u></h1>
        <div class="container-trui">
            <div class="c col-4">   
                <form action="#" onsubmit="return check()">
                    <fieldset>
                        <legend>Add job opportunity</legend>
                        <div class="form-group">
                            <label for="title">Job title: *</label>
                            <input type="text" class="form-control" id="title">
                        </div>
                        <div class="form-group">
                            <label for="img">Image:</label>
                            <input type="text" class="form-control" id="img">
                        </div>
                        <div class="form-group">
                            <label for="desc">Job description: *</label>
                            <textarea class="form-control" id="desc" rows="3"></textarea>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Add offer</button>
                </form>
            </div>
    
            <div class="col-4">
                <h3>View applicants</h3>
                <div style="border: 1px solid black; height: 400px"></div>
            </div>
        </div>
    </body>
</html>
