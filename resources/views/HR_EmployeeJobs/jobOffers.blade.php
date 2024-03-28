<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/jobs.css" rel="stylesheet" type="text/css"/>
    <link href="/css/nav.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Job offers</title>

</head>

<body>
    <div class="container-trui">
        <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Energy supplier</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Jobs</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('hiringManager')}}">Hiring manager</a>
                  </li>
                </ul>
              </div>
            </div>
        </nav>

        <div class="top-page">
          <img src="/images/energy-company.jpg" alt="green energy"/>
          <h1>Are you interested in working with us? Check out our different job offers</h1>
        </div>
        <div class="container">
            <h2 class="mb-3 text-dark" style="text-align: center; margin-top: 40px; margin-bottom: 30px">Join us!</h2>
            <p style="font-size: 18px">
              Discover a brighter, greener future with our commitment to reliability, innovation, and environmental stewardship. Join us in shaping a world where energy meets efficiency and sustainability. 
            </p>


            <h2 style="margin-top: 80px">We are looking for...</h2>
            <div class="container" id="cards-container">
                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Electrical engineer</h5>
                    </div>
                    <img src="{{URL('/images/careers_in_electrical_engineering.jpg')}}" class="card-img-top" alt="electrical engineer">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s </p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>

                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Project manager</h5>
                    </div>
                    <img src="{{URL('/images/project_manager.jpg')}}" class="card-img-top" alt="gas">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>

                  <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Wind turbine technician</h5>
                    </div>
                    <img src="{{URL('/images/wind-turbine-tech.png')}}" class="card-img-top" alt="meter">
                    <div class="card-body">
                      <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                      <a href="{{route('jobDescription')}}" class="card-link">Read more</a>
                    </div>
                  </div>
            </div>

            <!--<div class="container" id="cards-container2">
              <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Why choose us?</h5>
                </div>
                <img src="{{URL('/images/question.jpg')}}" class="card-img-top" alt="choose us">
                <div class="card-body">
                  <p class="card-text"> With a focus on innovation and environmental responsibility, we strive to empower our customers with the latest advancements...</p>
                  <a href="#" class="card-link">Read more</a>
                </div>
              </div>

              <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Meters</h5>
                </div>
                <img src="{{URL('/images/help.jpeg')}}" class="card-img-top" alt="meter">
                <div class="card-body">
                  <p class="card-text">Have any questions or are in need of our services or expertise? contact us in the link below</p>
                  <a href="#" class="card-link">Contact us</a>
                </div>
              </div>
            </div>-->
        </div>

        <!--footer -->
        <footer style="background-color: #5299d3; margin-top: 100px;">
          <div class="container p-4">
            <div class="row" style="display: flex; justify-content: space-between">
                <div class="col-md-5">
                    <p>Subscribe to our newsletter to never miss a job offer!</p>
                    <form action="#">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </div>
                    </form>
                </div>
              <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="mb-1 text-dark">opening hours</h5>
                <table class="table" style="border-color: #666;">
                  <tbody>
                    <tr>
                      <td>Mon - Fri:</td>
                      <td>8am - 9pm</td>
                    </tr>
                    <tr>
                      <td>Sat - Sun:</td>
                      <td>8am - 1am</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright: - Energy company powered by Green Energy.
          </div>
        </footer>
    </div>
</body>
</html>