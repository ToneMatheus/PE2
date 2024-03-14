<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <style>
        .edit-form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.1);
        }

        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }

        .edit-form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .edit-form button[type="submit"], .edit-form button[type="button"]{
            text-decoration: none;
            padding: 10px 20px;
            border: none;
            background-color: #2b3db6;
            color: white;
            cursor: pointer;
        }

        .edit-form button[type="submit"]:hover .edit-form button[type="button"]:hover{
            background-color: #0f1b68;
        }
    </style>
</head>
<body>
    <h1>Edit Customer</h1>

    <form class="edit-form" action="{{ url("/customer/{$customer->id}") }}" method="POST" onsubmit="return confirm('Are you sure you want to update this customer?');">
        @csrf
        @method('PUT')

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="{{ $customer->last_name }}" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="{{ $customer->first_name }}" required>

        <label for="phone_nbr">Phone Number:</label>
        <input type="text" id="phone_nbr" name="phone_nbr" value="{{ $customer->phone_nbr }}" required>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="{{ $customer->company_name }}">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Update</button>
        <button type="button" onclick="window.location='{{ url("/customerGridView") }}'">Back</button>
    </form>
</body>
</html>