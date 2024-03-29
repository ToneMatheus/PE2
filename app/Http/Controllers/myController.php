<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class myController extends Controller{
        public function profile(){
            return view('profile');
        }

        public function payslip(Request $request){
            // Retrieve the value of the 'flag' parameter
            $id = $request->input('id');
    
            // You can use $flag as needed
            return view('payslip', ['id' => $id]);
        }

        public function payList(){
            return view('payList');
        }

        public function contract(){
            return view('contract');
        }

        public function tariff(){
            return view('tariff');
        }

        public function contractOverview(){
            return view('customer.contractOverview');
        }

        public function manager(Request $request){
            $id = $request->input('manager_id');

            return view('managerPage', ['manager_id' => $id]);
        }

        public function employeeList(){
            return view('employeeList');
        }

        public function holiday(){
            return view('holidayRequestPage');
        }

        public function managerList(){
            return view('managerList');
        }
    }
?>