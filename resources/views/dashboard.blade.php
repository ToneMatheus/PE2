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

                        if (isset($team[0])) {
                            $teamName = DB::select("select team_name from teams where id = " . $team[0]->team_id);
                            $teamName = $teamName[0]->team_name;
                        } else {
                            // Handle the case where the array key doesn't exist
                            $teamName = null;
                        }
                    @endphp 

                    {{-- @if($roleId != config('roles.CUSTOMER') && !$changedDefault)
                        <script>window.location = "{{ route('password.request') }}"</script>
                    @endif --}}

                    @if($roleId == config('roles.MANAGER'))
                        {{-- for the helpdesk to see the ticket dashboard --}}
                        <a href="{{ route('ticket_dashboard') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket overview dashboard</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View everything about the tickets</p>
                            </div>
                        </a>
                        <a href="{{ route('manager.TicketStatus') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Dashboard</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View the Ticket dashboard</p>
                            </div>
                        </a>
                        <a href="{{ route('index-cron-job') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Job Scheduler</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage schedule of cron jobs</p>
                            </div>
                        </a>
                        <a href="{{ route('customerGridView') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Customer List</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage all our customers</p>
                            </div>
                        </a>
                        <a href="{{ route('managerticketoverview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View all tickets</p>
                            </div>
                        </a>
                        <a href="{{ route('Support_Pages.flowchart.Flowchart-ascalade-ticket2') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Flowchart</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Flowchart for ticket escalation</p>
                            </div>
                        </a>

                        <a href="{{ route('payouts') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Payouts</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Outstanding credit note payouts</p>
                            </div>
                        </a>

                        <a href="{{ route('manualInvoice') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Manual Invoice</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Generate an invoice for a customer</p>
                            </div>
                        </a>

                        <a href="{{ route('payment.create') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Add Payment</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Add an incoming payment</p>
                            </div>
                        </a>
                        <a href="{{ route('invoice_matching') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Invoice Matchings</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View invoice matchings of external payments</p>
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

                        {{-- <a href="{{ route('weeklyActivity') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Weekly activity</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Weekly employee performance</p>
                            </div>
                        </a>  --}}

                        <a href="{{ route('evaluations') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Employee evaluations</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">See your employee evaluations</p>
                            </div>
                        </a> 

                        <a href="{{ route('teamBenefits') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Team benefits</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Benefits earned by your employees</p>
                            </div>
                        </a>

                        <a href="{{ route('teamWeeklyReports') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Team weekly reports</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Weekly reports of your employees</p>
                            </div>
                        </a>

                        <a href="{{ route('Meter_History') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Meter History</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View meter history and enter index value as customer</p>
                            </div>
                        </a> 
                        {{-- @include('intranet.employeeIntranet'); --}}

                        @if($teamName == 'HR')
                            @include('chatbot.chatbotEmployeeHR')
                        
                            @elseif($teamName == 'Customer service')
                                <a href="{{ route('customerGridView') }}" class="block">
                                    <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                        <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Customer Overview</span>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">View all customers</p>
                                    </div>
                                </a> 
                                @include('chatbot.chatbotEmployeeCustomerService')
                            
                            @elseif($teamName == 'Meters')
                                @include('chatbot.chatbotEmployeeMeters')
                            
                            @elseif($teamName == 'Invoice')
                                <a href="{{ route('tariff') }}" class="block">
                                    <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                        <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Tariffs</span>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">Change the tariffs of products</p>
                                    </div>
                                </a>
                                @include('chatbot.chatbotEmployeeInvoice')
                        
                        @endif

                        @include('notifications.managerNotifications')
                            
                    @endif
                    
                    @if($roleId == config('roles.CUSTOMER_SERVICE'))
                    <a href="{{ route('ServiceTicketOverview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View all tickets</p>
                            </div>
                        </a>
                    @endif
                    @if($roleId == config('roles.BOSS'))
                        <a href="{{ route('submitted-ticket') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Submitted Tickets</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your submitted tickets</p>
                            </div>
                        </a>
                        <a href="{{ route('managerticketoverview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View all tickets</p>
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
                        {{-- for the helpdesk to see the ticket dashboard --}}
                        <a href="{{ route('ticket_dashboard') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket dashboard</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View everything about the tickets</p>
                            </div>
                        </a>



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

                        <a href="{{ route('viewAllMeters') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">All meters dashboard</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View all meters to be read</p>
                            </div>
                        </a>

                        <a href="{{ route('enter_index_employee') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Enter index values</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Enter index values</p>
                            </div>
                        </a>
                        <a href="{{ route('enter_index_paper') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Enter index values - paper</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Enter index values for customers who send in through paper</p>
                            </div>
                        </a>
                        <a href="{{ route('Meter_History') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Meter History</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View meter history and enter index value as customer</p>
                            </div>

                        </a> 
                        <a href="{{ route('ServiceTicketOverview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View all tickets</p>
                            </div>
                        </a>                        

                        {{-- @include('intranet.employeeIntranet'); --}}

                        @if($teamName == 'HR')
                            @include('chatbot.chatbotEmployeeHR')
                        
                        @elseif($teamName == 'Customer service')
                            <a href="{{ route('customerGridView') }}" class="block">
                                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                    <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Customer Overview</span>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">View all customers</p>
                                </div>
                            </a> 
                            @include('chatbot.chatbotEmployeeCustomerService')
                        
                        @elseif($teamName == 'Meters')
                            @include('chatbot.chatbotEmployeeMeters')
                        
                        @elseif($teamName == 'Invoice')
                            <a href="{{ route('tariff') }}" class="block">
                                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                    <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Tariffs</span>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">Change the tariffs of products</p>
                                </div>
                            </a>
                            
                            
                            @include('chatbot.chatbotEmployeeInvoice')
                
                        @endif
                </div>
                <div>

                        @include('intranet.employeeIntranet')
                        @include('notifications.notifications')
                    @endif

                    @if($roleId == config('roles.CUSTOMER_SERVICE'))
                        <!-- <a href="{{ route('ticket_dashboard') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket dashboard</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View everything about the tickets</p>
                            </div>
                        </a> -->
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

                        <a href="{{ route('ticket_overview') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Ticket Overview</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View your tickets</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('Meter_History') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Meter History</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">View meter history and enter index value as customer</p>
                            </div>
                        </a> 

                        @include('chatbot.chatbot');
                    @endif
                    @if($roleId == config('roles.CUSTOMER_SERVICE'))
                        <a href="{{ route('Support_Pages.flowchart.Flowchart-ascalade-ticket') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Flowchart</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Flowchart for ticket escalation</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
