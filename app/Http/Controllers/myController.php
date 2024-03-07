<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class myController extends Controller{
        public function profile(){
            return view('profile');
        }

        public function payslip(){
            return view('payslip');
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
    }
?>