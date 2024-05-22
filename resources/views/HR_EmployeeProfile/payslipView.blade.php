<div class="general-details" style="padding-bottom: 100px;">
    <div style="border-right: 2px solid black;">
        <div style="border-bottom: 2px solid black; padding-right: 300px; padding-bottom: 20px">
            <b>Energy Company</b><br/>
            Jan Pieter de Nayerlaan 5<br/>
            2860 Sint-Katelijne-Waver<br/>
            <b>EmployeeID:</b> {{$userID}}
        </div>

        <div style="padding-right: 300px; padding-top: 20px">
            <b>Billing date</b><br/>
            <b>Work period:</b> From {{$start}} to {{$end}}<br/>
            <b>Date calculated:</b> {{$issued}}
        </div>
    </div>
    
    <div>
        <div style="border-bottom: 2px solid black; padding-left: 20px; padding-bottom: 20px">
            <b>Name:</b> {{$firstName}} {{$lastName}}<br/>
            {{$street}}, {{$num}} {{$box}}<br/>
            {{$pC}} {{$city}}, {{$province}}
        </div>

        <div style="padding-left: 20px; padding-top: 20px">
            <b><u>Contract details:</u></b><br/>
            <b>Title:</b> {{$role[0]->role_name == 'Employee' ? "normal " . $role[0]->role_name : "" . $role[0]->role_name}}<br/>
            {{-- <b>Department:</b> {{$dept}} --}}
        </div>
    </div>
</div>

<table>
    <tr>
        <th>Amount per hour: {{$amountPerHour}}$</th>
    </tr>

    <tr class="col-name">
        <th>Description</th><th>Days</th><th>Hours</th><th style="text-align: center">Amount</th>
    </tr>

    <tr>
        <td>Days worked - brut</td>
        <td>{{$daysWorked}}</td>
        <td>{{$hours}}</td>
        <td style="text-align: center"><b>{{$totalAmount}}$</b></td>
    </tr>

    <tr>
        <td>Holidays taken</td>
        <td>{{$differenceInDays}}</td>
        <td>/</td>
        <td style="text-align: center">
            {{$differenceInDays}} * {{$amountPerHour}}<br/>
            {{$totalAmount - $newAmount = $newTotalAmount}}
        </td>
    </tr>

    <tr>
        <td colspan="3">TVA</td>
        <td style="text-align: center">
            {{$newTotalAmount}} * {{0.21}} =
            {{$TVA}}
        </td>
    </tr>

    <tr>
        <td colspan="3">Salary - net</td>
        <td style="text-align: center">{{$newTotalAmount}}$ - {{$TVA}}$<br/><b>{{$total}}$</b></td>
    </tr>

    <tr style="height: 170px">
        <td colspan="3"></td>
        <td></td>
    </tr>

    <tr>   
        <td colspan="3"></td >
        <td class="total"><b>Total</b></td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: right;">Sent to the account <b>{{$IBAN}}</b> Name: <b>{{$firstName}} {{$lastName}}</b></td>
        <td style="text-align: center"><b>{{$total}}$</b></td>
    </tr>
</table>