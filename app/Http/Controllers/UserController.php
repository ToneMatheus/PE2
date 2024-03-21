<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;

class UserController extends Controller
{
    // Dummy user data array
    private $users = [
        ["id" => 1, "name" => "John Doe", "address" => "123 Main St", "dob" => "1990-01-01", "connection_type" => "electricity"],
        ["id" => 2, "name" => "Jane Smith", "address" => "456 Elm St", "dob" => "1985-05-15", "connection_type" => "gas"],
        ["id" => 3, "name" => "Alice Johnson", "address" => "789 Oak St", "dob" => "1978-12-10", "connection_type" => "both"],
        ["id" => 4, "name" => "Bob Brown", "address" => "101 Pine St", "dob" => "1982-09-20", "connection_type" => "electricity"],
        ["id" => 5, "name" => "Eve Wilson", "address" => "202 Cedar St", "dob" => "1995-03-25", "connection_type" => "gas"]
    ];

    // Show the form to enter user ID
    public function index()
    {
        return view('user.index');
    }

    // Retrieve user data based on ID
    public function getUserData(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $id = $validatedData['id'];

        // Find user by ID
        $user = array_filter($this->users, function ($user) use ($id) {
            return $user['id'] == $id;
        });

        if (!empty($user)) {
            $user = reset($user); // Get the first element of the filtered array
            return view('user.show', compact('user'));
        } else {
            return "User not found.";
        }
    }
}?>
