<?php

namespace App\Http\Controllers;

use App\Events\NewEmployeeRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Employee_Profile,
    User,
    Employee_contract,
    Address,
    Team,
    TeamMember,
    Role,
    User_Role,
    Customer_Address,
    Balance
};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class EmployeeController extends Controller
{
    public function showEmployees() {
        $employees = User::whereNotNull('employee_profile_id')
        ->join('team_members as tm', 'tm.user_id', '=', 'users.id')
        ->join('teams as t', 't.id', '=', 'tm.team_id')
        ->where('tm.is_active', '=', 1)
        ->where('users.is_active', '=', 1)
        ->paginate(5); 
        
        $teams = Team::all();
        $roles = Role::all();

        return view('employeeOverview', ['employees' => $employees, 'teams' => $teams, 'roles' => $roles]);
    }

    public function processEmployee(Request $request) {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'name' => 'required',
            'title' => 'required',
            'nationality' => 'required',
            'personalEmail' => 'required|email',
            'phoneNbr' => 'required',
            'birthDate' => 'required|date',
            'street' => 'required',
            'number' => 'required|numeric',
            'box' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postalCode' => 'required|numeric',
            'contractType' => 'required',
            'startDate' => 'required|date',
            'endDate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = $request->input('startDate');
                    if (!is_null($value) && $value <= $startDate) {
                        $fail('The end date must be later than the start date.');
                    }
                }
            ],
            'salary' => 'required|numeric|min:1700',
            'team' => 'required',
            'role' => 'required',
        ], [
            'firstName.required' => 'The first name is required.',
            'name.required' => 'The last name is required.',
            'title.required' => 'The title is required.',
            'nationality.required' => 'The nationality is required.',
            'personalEmail.required' => 'The personal email is required.',
            'personalEmail.email' => 'The personal email must be a valid email address.',
            'phoneNbr.required' => 'The phone number is required.',
            'birthDate.required' => 'The birth date is required.',
            'birthDate.date' => 'The birth date must be a valid date.',
            'street.required' => 'The street is required.',
            'number.required' => 'The number is required.',
            'number.numeric' => 'The number must be numeric.',
            'box.required' => 'The box is required.',
            'city.required' => 'The city is required.',
            'province.required' => 'The province is required.',
            'postalCode.required' => 'The postal code is required.',
            'postalCode.numeric' => 'The postal code must be numeric.',
            'contractType.required' => 'The contract type is required.',
            'startDate.required' => 'The start date is required.',
            'startDate.date' => 'The start date must be a valid date.',
            'endDate.required' => 'The end date is required.',
            'endDate.date' => 'The end date must be a valid date.',
            'salary.required' => 'The salary is required.',
            'salary.numeric' => 'The salary must be numeric.',
            'salary.min' => 'The salary must be at least 1700.',
            'team.required' => 'The team is required.',
            'role.required' => 'The role is required.',
        ]);

        if($validator->fails()){
            return redirect()->route('employees')->withErrors($validator)->withInput();
        }

        //new Employee_profile
        $employee = Employee_profile::create([
            'hire_date' => $request->input('startDate'),
        ]);

        //username & email generated
        $username = $request->input('firstName')[0] . $request->input('name')[0] . $employee->id;
        $email = $request->input('firstName')[0] . $request->input('name')[0] . $employee->id . '@example.com';

        //new Employee_contract
        Employee_contract::create([
            'employee_profile_id' => $employee->id,
            'start_date' => $request->input('startDate'),
            'end_date' => $request->input('endDate'),
            'type' => $request->input('type'),
            'status' => 'active',
            'salary_per_month' => $request->input('salary')
        ]);

        //new User
        $userData = [
            'username' => $username,
            'password' => Hash::make('default'),    //mail to change  
            'email' => $email,
            'personal_email' => $request->input('personalEmail'), //bound that change
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('name'),
            'employee_profile_id' => $employee->id,
            'phone_nbr' => $request->input('phoneNbr'),
            'birth_date' => $request->input('birthDate'),
            'title' => $request->input('title'),
            'nationality' => $request->input('nationality')
        ];

        $user = User::create($userData);

        $role = Role::where('role_name', '=', $request->input('role'))
        ->first();

        //new User_role
        User_Role::create([
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
        ];

        $address = Address::create($addressData);

        Customer_Address::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'start_date' => $request->input('startDate')
        ]);

        Balance::create([
            'employee_profile_id' => $employee->id,
            'holiday_type_id' => 1,
            'yearly_holiday_credit' => 20,
            'used_holiday_credit' => 0,
            'start_date' => $request->input('startDate')
        ]);

        event(new NewEmployeeRegistered($employee, $user));

        return redirect()->route('employees');
    }

    public function editEmployee($eID) {
        $employee = User::join('employee_profiles as ef', 'ef.id', '=', 'users.employee_profile_id')
        ->join('employee_contracts as ec', 'ec.employee_profile_id', '=', 'users.employee_profile_id')
        ->join('team_members as tm', 'tm.user_id', '=', 'users.id')
        ->join('teams as t', 't.id', '=', 'tm.team_id')
        ->join('user_roles as ur', 'ur.user_id', '=', 'users.id')
        ->join('roles as r', 'r.id', '=', 'ur.role_id')
        ->join('customer_addresses as ca', 'ca.user_id', '=', 'users.id')
        ->join('addresses as a', 'a.id', '=', 'ca.address_id')
        ->where('users.employee_profile_id', '=', $eID)
        ->where('ur.is_active', '=', 1)
        ->where('tm.is_active', '=', 1)
        ->where('a.is_billing_address', '=', 1)
        ->where(function($query) {
            $query->where('ec.end_date', '>', now())
                  ->orWhereNull('ec.end_date');
        })
        ->first();

        $teams = Team::all();
        $roles = Role::all();

        return view('editEmployee', ['employee' => $employee, 'teams' => $teams, 'roles' => $roles]);
    }

    public function editPersonalEmployee(Request $request, $eID) {
        $user = User::where('employee_profile_id', '=', $eID)
        ->first();

        $user->first_name = $request->input('firstName');
        $user->last_name = $request->input('name');
        $user->title = $request->input('title');
        $user->phone_nbr = $request->input('phoneNbr');
        $user->birth_date = $request->input('birthDate');
        $user->nationality = $request->input('nationality');
        $user->personal_email = $request->input('personalEmail');
        $user->save();

        return redirect()->route('employees.edit', ['eID' => $eID]);
    }

    public function editAddressEmployee(Request $request, $eID, $aID, $uID){
        $address = Address::find($aID);

        $address->street = $request->input('street');
        $address->number = $request->input('number');
        $address->box = $request->input('box');
        $address->city = $request->input('city');
        $address->province = $request->input('province');
        $address->postal_code = $request->input('postalCode');

        $address->save();

        return redirect()->route('employees.edit', ['eID' => $eID]);
    }

    public function editContractEmployee(Request $request, $eID, $uID){
        $employeeContract = Employee_contract::where('employee_profile_id', '=', $eID)
        ->where(function($query) {
            $query->where('end_date', '>', now())
                  ->orWhereNull('end_date');
        })
        ->first();

        if($employeeContract->type !== $request->input('type')){
            $employeeContract->start_date = Carbon::now()->format('y/m/d');
        }
        
        $employeeContract->end_date = $request->input('endDate');
        $employeeContract->type = $request->input('type');
        $employeeContract->salary_per_month = $request->input('salary');
        $employeeContract->save();

        $oldTeam = Team::join('team_members as tm', 'tm.team_id', '=', 'teams.id')
        ->select('teams.id as tID', 'tm.id as tmID')
        ->where('tm.is_active', '=', 1)
        ->where('tm.user_id', '=', $uID)
        ->first();

        if($oldTeam->team_name !== $request->input('team')){
            $oldTeamMember = TeamMember::where('id', '=', $oldTeam->tmID)
            ->first();

            $oldTeamMember->is_active = 0;
            $oldTeamMember->save();

            $newTeam = Team::where('team_name', '=', $request->input('team'))
            ->first();

            TeamMember::create([
                'user_id' => $uID,
                'team_id' => $newTeam->id
            ]);
        }

        $oldRole = User_Role::where('user_id', '=', $uID)
        ->where('is_active', '=', 1)
        ->first();

        if($oldRole->role_name !== $request->input('role')){
            $oldRole->is_active = 0;
            $oldRole->save();

            $newRole = Role::where('role_name', '=', $request->input('role'))
            ->first();

            User_Role::create([
                'user_id' => $uID,
                'role_id' => $newRole->id
            ]);
        }

        return redirect()->route('employees.edit', ['eID' => $eID]);
    }
}
