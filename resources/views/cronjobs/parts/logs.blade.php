<table id="LogsTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
        Scheduled Logs
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400"></p>
    </caption>
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">Invoice ID:</th>
            <th scope="col" class="px-6 py-3">
                <div class="flex items-center">
                    Log Level:
                    <a href="#">
                        <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                        </svg>
                    </a>
                </div>
            </th>
            <th scope="col" class="px-6 py-3">Message:</th>
        </tr>
    </thead>
    <tbody>
        @if (!$jobLogs->isEmpty())
        @foreach ($jobLogs as $jobLog)
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                @if ($jobLog->invoice_id != null)
                    {{ $jobLog->invoice_id }}
                @else
                    N/A
                @endif    
            </td>
            <td class="px-6 py-4">
                {{ $jobLog->log_level }}
            </td>
            <td class="px-6 py-4">
                {{ $jobLog->message }}
            </td>
        </tr>
        @endforeach
        @else
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td rowspan="3" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                There are no logs for this run.
            </td>
        </tr>
        @endif
    </tbody>
</table>