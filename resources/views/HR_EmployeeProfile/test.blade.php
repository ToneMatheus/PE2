@php
    use App\Helpers\RoleHelper;

    $roleHelper = new RoleHelper;
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <ul>
            <li><a href="{{route('profile')}}">Profile</a></li>

            @if($roleHelper->hasRole($user->ID, 'Finance analyst'))
                <li><a href="{{route('tariff')}}">Tariff</a></li>
                <li><a href="{{route('invoice_query')}}">Invoice Query</a></li>
                <li><a href="{{route('unpaid_invoice_query')}}">Unpaid Invoice Query</a></li>
            @endif
        </ul>
    </body>
</html>