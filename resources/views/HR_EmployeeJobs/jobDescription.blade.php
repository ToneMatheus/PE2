<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/nav.css" rel="stylesheet" type="text/css"/>
    <link href="/css/jobDescription.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Job description</title>

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
                <a class="nav-link" href="{{route('hiringManager')}}">Hiring manager</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <div class="top-page">
      <img src="/images/cables.jpg" alt="electric cables"/>
      <h1>Job description</h1>
    </div>

    <div class="container c">
        <div class="content">
          <span>D</span>iscover the power of innovation and reliability with our company's electricity offerings. Here, we understand that electricity is the lifeblood of modern living, and we are dedicated to providing a seamless and dependable supply to meet the energy demands of homes and businesses alike.
    
            Our electricity services are designed to empower you with energy solutions that not only meet your immediate needs but also contribute to a sustainable and eco-friendly future. We offer a diverse range of electricity plans, from standard packages to customizable options, ensuring that you have the flexibility to choose the plan that best aligns with your lifestyle or business requirements.
            
            <br/><br/>Committed to environmental responsibility, we prioritize the integration of renewable energy sources into our electricity generation. By harnessing the power of clean and sustainable technologies, we aim to reduce our carbon footprint and contribute to a greener planet.
            
            We pride ourselves on a customer-centric approach, providing transparent billing, responsive customer support, and innovative solutions to enhance your overall energy experience. Whether you're looking for cost-effective electricity plans, smart energy management options, or a commitment to environmental stewardship, we have the expertise to fulfill your energy aspirations.
            
            <br/><br/>Join us in embracing a future where electricity is not just a utility but a force for positive change. Choose us for electricity that powers progress, reliability that empowers, and a commitment to a brighter, sustainable tomorrow.
        </div>
    </div>

    <div class="mx-auto" style="text-align: center">
        <h4>Does that description fit you? then <a href="#"><b>Apply now</b></a></h4>
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
</body>
