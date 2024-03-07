{{-- -*-html-*- --}}

  <div>

    <h1>Ticket submitted successfully</h1>

    <p>Thank you for submitting your ticket! We will try and resolve it as
      quickly as possible.</p>

    <ul>
      <li><strong>{{ $customerticket->issue }}</strong></li>
      <li><strong>{{ $customerticket->description }}</strong></li>
    </ul>

  </div>

