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

        public function manager(){
            return view('managerPage');
        }

        public function employeeList(){
            return view('employeeList');
        }
    }
?>