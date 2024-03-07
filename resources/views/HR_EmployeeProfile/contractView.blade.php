@php
//making variables to hold the info that way i don't have to do much when its time to actually fetch the data from the database
$contract_issue_date = "29/02/2024";
$contract_start_date = "01/30/2024";
$company_name = "Energy company";
$company_address = "Jan Pieter de Nayerlaan 5, 2860 Sint-Katelijne-Waver";
$employee_name = "John Doe";
$employee_address = "Dummy street 25, 1900 Wonderland";
$job_title = "Maintainance manager";
$responsibilities = "Meter maintainace, pipes, gas...";
$salary = 2000.90;
$account_number = "BE23 2341 1234 2523";

//contract body
    echo("<h1><u>Employee Contract</u></h1>

        <p>This Employment Contract is issued on the $contract_issue_date between $company_name, located at $company_address, and $employee_name, residing at $employee_address.</p>

        <h4>1. Position and Responsibilities</h4>

        <p>Employee agrees to accept the position of $job_title with the Company. Employee's responsibilities will include but are not limited to $responsibilities.</p>

        <h4>2. Compensation</h4>

        <p> Employee will receive a monthly salary of $salary$ payable by card on the following account number: $account_number. In addition to the base salary, Employee may be eligible for bonuses or other forms of compensation as determined by the Company's policies.</p>

        <h4>3. Employment Status</h4>

        <p>Employee's employment with the Company is full-time and will begin on $contract_start_date. During the time of this employement, either party may terminate the employment relationship with or without cause and with or without notice but with a 2 weeks notice.</p>

        <h4>4. Benefits</h4>

        <p>Employee will be eligible for the Company's benefits program, including but not limited to health insurance, retirement plans, and paid time off, in accordance with the Company's policies.</p>

        <h4>5. Confidentiality</h4>

        <p>During the course of employment, Employee may have access to confidential information of the Company. Employee agrees not to disclose any confidential information to any third party and to use such information solely for the benefit of the Company.</p>

        <h4>6. Non-Compete</h4>

        <p>Employee agrees not to engage in any activities that directly compete with the Company during the term of employment and for a period of 5 months after the termination of employment.</p>

        <h4>7. Termination</h4>

        <p>This Contract may be terminated by either party with a two weeks written notice to the other party. The Company reserves the right to terminate Employee's employment at any time for any reason not prohibited by law.</p>

        <h4>8. Governing Law</h4>

        <p>This Contract shall be governed by and construed in accordance with the laws of Belgium.</p>

        <p>In witness whereof, the parties have executed this Contract as of the date first above written.</p>

        <p>$company_name</p>

        <p>By: CEO</p>

        <p>$employee_name</p>

        <p>Date: $contract_issue_date </p> ");
@endphp
