@php
    $holidayReq = DB::select("select * from holidays inner join users on holidays.employee_profile_id = users.employee_profile_id where users.id = " . Auth::id());

    $manager_user = DB::select("select * from users where id = " . Auth::id());
    
    $team_members = [];
    $manager_team = DB::select("select team_id from team_members where user_id = " . Auth::id());
    $employee_manager_relation = DB::select("select * from team_members where team_id = " . $manager_team[0]->team_id . " and is_manager = 0");

    $all_requests = [];
    $all_data = [];//to select everything in the table

    foreach ($employee_manager_relation as $relation) {
        $team_members = DB::select("select employee_profile_id from users where id = $relation->user_id");
        $emp_profile_id = $team_members[0]->employee_profile_id;
        
        if(!empty($emp_profile_id)){
            $requests = DB::select("SELECT * FROM holidays WHERE employee_profile_id = $emp_profile_id AND is_active = 1");
            $all_requests = array_merge($all_requests, $requests);

            $table_data = DB::select("select * from holidays where employee_profile_id = $emp_profile_id");
            $all_data = array_merge($all_data, $table_data);
        }
        
    }

    use Carbon\Carbon;
@endphp

<div id="notificationBell" class="fixed bottom-0 left-0 bg-gray-900 flex items-center justify-center rounded-full" style="margin: 30px; background-color: white; width: 70px; height: 70px">
    <img src="/images/notifications.png" style="width: 35px" alt="notification-bell"/>
</div>

<div id="notificationContainer" class="fixed bottom-0 left-0 w-1/2 bg-gray-900 p-4 text-white rounded-tl-lg rounded-tr-lg" style="display: none;">
    <div id="notificationContent" class="flex justify-between items-center">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <p id="notificationMessage" class="text-sm"></p>
        </div>
        <button id="dismissNotification" class="text-sm ml-4">Dismiss</button>
    </div>
</div>

@php $i = 1; @endphp
@if (!empty($all_requests))
    @foreach ($all_requests as $request)
        @php
            $employee_id = $request->employee_profile_id;
            $request_id = $request->id;
            $holiday_type = $request->holiday_type_id;
            $fullname = DB::select("select first_name, last_name from users where employee_profile_id = $employee_id");
            $holiday_type_name = DB::select("select * from holiday_types where id = $holiday_type");
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $diffInDays = $endDate->diffInDays($startDate) + 1;
            
            // Concatenate holiday request details
            $holidayDetails = $fullname[0]->first_name . ' ' . $fullname[0]->last_name . ' requested ' . $diffInDays . ' days off.';
        @endphp
        {{-- Output holiday request details --}}
        <input type="hidden" id="holiday_request_{{ $i }}" value="{{ $holidayDetails }}">
        @php $i++; @endphp
    @endforeach
@endif

<script>
    $(document).ready(function() {
        function showNotificationBasedOnCondition(condition) {
            if (condition) {
                // Iterate through holiday requests and concatenate details
                var notificationMessage = '';
                @for ($j = 1; $j < $i; $j++)
                    var holidayDetails = $('#holiday_request_{{ $j }}').val();
                    notificationMessage += holidayDetails + '<br>';
                @endfor
                showNotification(notificationMessage);
            } else {
                showNotification('No new notifications');
            }
        }

        function showNotification(message) {
            $('#notificationMessage').html('<p>' + message + '</p>');
            $('#notificationContainer').slideDown();
            $('#notificationBell').hide();
        }

        $('#notificationContainer').on('click', '#dismissNotification', function() {
            $('#notificationContainer').slideUp();
            $('#notificationBell').show();
        });

        $('#notificationBell').click(function() {
            $('#notificationContainer').slideToggle();
            $(this).hide();
        });

        setTimeout(function() {
            showNotificationBasedOnCondition({{ !empty($all_requests) ? 'true' : 'false' }});
        });
    });
</script>

