<x-app-layout :title="'Dashboard'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-3 gap-4">
                    @php
                        $roleId = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                        // $changedDefault = DB::table('users')->where('id', Auth::id())->first()->changed_default;
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
                        @include('chatbot.chatbot')
                    @endif
                </div>
                
                <style>
                    .card:hover{
                        box-shadow: 0px 0px 5px rgb(188, 187, 187);
                    }
                </style>

                <div class="intranet" style="margin: 20px 30px 30px 28px; display: flex; justify-content: space-between;">
                    <div class="left-side">
                        <div class="card" style="display: flex; width: 600px; border-radius: 5px">
                            <img class="card-img" src="/images/cables.jpg" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
                            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                              <h4 class="card-title h4">Benefits and compensation</h4>
                              <p class="card-text">Some example text some example text. John Doe is an architect and engineer</p>
                              <a href="#" class="btn btn-primary">See Profile</a>
                            </div>
                        </div>
        
                        <div class="card" style="display: flex; width: 600px; border-radius: 5px; margin-top: 20px;">
                            <img class="card-img" src="/images/cables.jpg" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
                            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                              <h4 class="card-title h4">Benefits and compensation</h4>
                              <p class="card-text">Some example text some example text. John Doe is an architect and engineer</p>
                              <a href="#" class="btn btn-primary">See Profile</a>
                            </div>
                        </div>
    
                        <div class="card" style="display: flex; width: 600px; border-radius: 5px; margin-top: 20px;">
                            <img class="card-img" src="/images/cables.jpg" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
                            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
                              <h4 class="card-title h4">Benefits and compensation</h4>
                              <p class="card-text">Some example text some example text. John Doe is an architect and engineer</p>
                              <a href="#" class="btn btn-primary">See Profile</a>
                            </div>
                        </div>
                    </div>

                    <div class="right-side">
                        <h1>Company news</h1>
                        <p>Renewable energy by 2035?</p>
                        <p>Read about out company's approach to this</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
