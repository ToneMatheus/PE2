<x-app-layout :title="'Dashboard'">    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-3 gap-4">
                    @php
                        $roleId = DB::table('user_roles')->where('user_id', Auth::id())->first()->role_id;
                    @endphp
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

                        <a href="{{ route('customerGridView') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">Customer list</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage customers</p>
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
                    <a href="{{ route('index-cron-job') }}" class="block">
                            <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                                <span class="text-blue-500 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2">TEST</span>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">TEST</p>
                            </div>
                        </a>
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
