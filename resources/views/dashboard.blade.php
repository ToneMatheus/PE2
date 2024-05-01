<x-app-layout :title="'Dashboard'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-3 gap-4">
                    @php
                        $roleId = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                        // $changedDefault = DB::table('users')->where('id', Auth::id())->first()->changed_default;

                        //selecting each user's team
                        $team = DB::select("select team_id from team_members where user_id = " . Auth::id());
                        $teamName = DB::select("select team_name from teams where id = " . $team[0]->team_id);
                        $teamName = $teamName[0]->team_name;
                    @endphp 

                    {{-- @if($roleId != config('roles.CUSTOMER') && !$changedDefault)
                        <script>window.location = "{{ route('password.request') }}"</script>
                    @endif --}}

                    @if($roleId == config('roles.MANAGER'))
                        <a href="{{ route('create-ticket') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Create Ticket</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Create a new ticket</p>
                            </div>
                        </a>
                        <a href="{{ route('index-cron-job') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Job Scheduler</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage schedule of cron jobs</p>
                            </div>
                        </a>

                        {{-- for the managers to manage their users --}}
                        <a href="{{ route('managerPage') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Team management</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage your employees</p>
                            </div>
                        </a>

                        {{-- for the employees to manage their holiday requests --}}
                        <!-- <a href="{{ route('request') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Holiday request</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage your holidays</p>
                            </div>
                        </a> -->
                        {{-- for the employees to manage their profile --}}
                        <a href="{{ route('profile') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Your profile</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your profile information</p>
                            </div>
                        </a> 

                        <a href="{{ route('employeeBenefits') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Your benefits</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">See your benefits as manager</p>
                            </div>
                        </a> 

                        <a href="{{ route('weeklyActivity') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Weekly activity</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Weekly employee performance</p>
                            </div>
                        </a> 

                        @if($teamName == 'HR')
                            @include('chatbot.chatbotEmployeeHR');
                        
                            @elseif($teamName == 'Customer service')
                                @include('chatbot.chatbotEmployeeCustomerService');
                            
                            @elseif($teamName == 'Meters')
                                @include('chatbot.chatbotEmployeeMeters');
                            
                            @elseif($teamName == 'Invoice')
                                @include('chatbot.chatbotEmployeeInvoice');
                        
                        @endif

                        @include('notifications.managerNotifications');
                            
                    @endif
                    @if($roleId == config('roles.BOSS'))
                        <a href="{{ route('submitted-ticket') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Submitted Tickets</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your submitted tickets</p>
                            </div>
                        </a>
                    @endif
                    @if($roleId == config('roles.FINANCE_ANALYST'))
                        <a href="{{ route('show-ticket') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Show Ticket</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View a specific ticket</p>
                            </div>
                        </a>
                    @endif 
                    @if($roleId == config('roles.EMPLOYEE'))
                        {{-- for the employees to manage their holiday requests --}}
                        <a href="{{ route('request') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Holiday request</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage your holidays</p>
                            </div>
                        </a>

                        {{-- for the employees to see their different documents --}}
                        <a href="{{ route('documents') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Documents</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Access your documents</p>
                            </div>
                        </a>
                        {{-- for the employees to see their profile information --}}
                        <a href="{{ route('profile') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Your profile</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your profile information</p>
                            </div>
                        </a> 

                        @include('intranet.employeeIntranet');

                        @if($teamName == 'HR')
                            @include('chatbot.chatbotEmployeeHR');
                        
                        @elseif($teamName == 'Customer service')
                            @include('chatbot.chatbotEmployeeCustomerService');
                        
                        @elseif($teamName == 'Meters')
                            @include('chatbot.chatbotEmployeeMeters');
                        
                        @elseif($teamName == 'Invoice')
                            @include('chatbot.chatbotEmployeeInvoice');
                        
                        @endif

                        @include('notifications.notifications');
                    @endif
                    @if($roleId == config('roles.CUSTOMER'))
                        <a href="{{ route('customer.invoiceStatus') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Invoice Status</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your invoice status</p>
                            </div>
                        </a>
                        <a href="{{ route('contract_overview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Contract Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your contract overview</p>
                            </div>
                        </a>
                        @include('chatbot.chatbot');
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
