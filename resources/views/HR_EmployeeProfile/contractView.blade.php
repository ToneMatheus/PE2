
<u><h1 style="text-align: center">Employee Contract</h1></u>
<p>This agreement is made effective as of {{$contract_start_date}}, between:</p>

<h5>Employer:</h5>
<p>{{$company_name}}<br>
Located at: {{$company_address}}</p>

<h5>Employee:</h5>
<p>Name: {{$employee_name}}<br>
Address: {{$employee_address}}</p>
<hr/>

<h4>1. Position and Duties:</h4>
<p>The Employer agrees to employ the employee as {{$role[0]->role_name == 'Employee' ? "a regular " . $role[0]->role_name : "a " . $role[0]->role_name}}. The Employee agrees to perform the duties and responsibilities associated with this position to the best of their abilities. The duties may be amended by the Employer from time to time.</p>

<h4>2. Compensation:</h4>
<p>Your compensation as an employee will heavily depend on your role in the company. Your current role is <b>{{$role[0]->role_name}}</b> and for that your salary ranges from <b>{{$salary[0]->min_salary}}$</b> to <b>{{$salary[0]->max_salary}}$</b> depending on whether or not you have passed the employee evaluation. You should also note that this taxes in accordance with the belgian law will be reduced from this with a <b>TVA of 21%</b></p>

<h4>3. Payment and Benefits:</h4>
<p>The Employee's compensation will be paid monthly through direct deposit to the bank account specified by the Employee. The account number for direct deposit is <b>{{$account_number}}</b>. </p> <h5>Your benefits as an employee include:</h5> <ul>                        
    @foreach($benefits as $benefit)
        <li>{{$benefit->description}} </li>
    @endforeach</ul></p>

<h4>4. Working Hours:</h4>
<p>The standard work hours for the Employee shall be 30 hours per week. This time is not fixed, as it would differ from time to time depending on the employee. Overtime may be required from time to time, and will be compensated in accordance with applicable labor laws.</p>

<h4>5. Probation Period:</h4>
<p>The Employee's employment is subject to a probationary period of one month, during which either party may terminate the employment relationship with written notice.</p>

<h4>6. Confidentiality:</h4>
<p>The Employee agrees not to disclose any confidential information belonging to the Employer, including but not limited to trade secrets, business plans, and customer information, during or after employment.</p>
 
<h4>7. Termination:</h4>
<p>Either party may terminate this agreement with written notice. Upon termination, the Employee agrees to return all company property and materials in their possession.</p>

<h4>8. Non-Compete:</h4>
<p>During the term of employment and for a period of 5 months after termination, the Employee agrees not to engage in any activity that competes with the Employer's business interests within the various geographical locations of their different offices.</p>

<h4>9. Governing Law:</h4>
<p>This agreement shall be governed by and construed in accordance with the laws of Belgium.</p>

<h4>10. Entire Agreement:</h4>
<p>This agreement constitutes the entire understanding between the parties and supersedes all prior agreements and understandings, whether written or oral.</p>

<p>By signing below, the parties acknowledge and agree to the terms and conditions set forth in this agreement.</p><br/>

<p><i style="font-size: 20px">{{$employee_name}}</i></p>

<p><i style="font-size: 20px">{{$company_name}}</i></p>
