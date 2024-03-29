<?php
    namespace App\Http\Controllers;

    use PDF;
    use Illuminate\Http\Request;

    class DomPDFController extends Controller{
        public function getPaySlipPDF(Request $request){
            $data = [
                'title' => 'Your Payslip'
            ];

            $pdf = PDF::loadView('payslipView', $data);

            return $pdf->download('payslip.pdf');
        }

        public function getContractPDF(Request $request){
            $data = [
                'title' => 'Your Contract'
            ];

            $pdf = PDF::loadView('contractView', $data);

            return $pdf->download('contract.pdf');
        }

        public function getBenefitsPDF(Request $request){
            $data = [
                'title' => 'Your benefits'
            ];

            $pdf = PDF::loadView('employeeBenefitsView', $data);

            return $pdf->download('employee_benefits.pdf');
        }
    }
?>