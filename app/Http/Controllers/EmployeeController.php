<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee_Profile;
use App\Models\User;
use App\Models\Employee_contract;
use App\Models\Address;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function showEmployees() {
        $teams = Team::all();

        $roles = DB::table('roles')
        ->get();

        return view('employeeOverview', ['teams' => $teams, 'roles' => $roles]);
    }

    public function processEmployee(Request $request) {
        //new Employee_profile
        $employeeData = [
            'hire_date' => $request->input('startDate'),
            'nationality' => $request->input('nationality')
        ];

        $employee = Employee_profile::create($employeeData);

        //new Employee_contract
        Employee_contract::create([
            'employee_profile_id' => $employee->id,
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'type' => $request->input('type'),
            'status' => 'active',
            'salary_per_month' => $request->input('salary')
        ]);

        //username & email generated
        $username = $request->input('firstName')[0] . $request->input('name')[0] . $employee->id;
        $email = $request->input('firstName') . $request->input('name') . '@example.com';

        //new User
        $userData = [
            'username' => $username,
            'password' => 'default',    //mail to change  
            'email' => $email,
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('name'),
            'employee_profile_id' => $request->input($employee->id),
            'phone_nbr' => $request->input('phoneNbr'),
            'birth_date' => $request->input('birthDate'),
            'title' => $request->input('title'),
        ];

        $user = User::create($userData);

        $role = DB::table('roles')
        ->where('role_name', '=', $request->input('role'))
        ->first();

        //new User_role
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id
        ]);

        $team = Team::where('team_name', '=', $request->input('team'))
        ->first();

        //new Team_member
        //without manager
        TeamMember::create([
            'user_id' => $user->id,
            'team_id' => $team->id
        ]);

        //new Address
        $addressData = [
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'box' => $request->input('box'),
            'postal_code' => $request->input('postalCode'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'country' => 'Belgium',
            'type' => 'house', //bound to be removed
        ];

        $address = Address::create($addressData);

        DB::table('customer_addresses')->insert([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'start_date' => $request->input('startDate')
        ]);

        return redirect()->route('employeeOverview');
    }
}
