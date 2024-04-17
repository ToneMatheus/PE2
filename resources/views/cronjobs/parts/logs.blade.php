<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
        Scheduled Logs
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
            @foreach($logCounts as $logCount)
                {{ ucfirst($logCount->log_level) }}: {{ $logCount->count }}
            @endforeach
        </p>
    </caption>
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">Invoice ID:</th>
            <th scope="col" class="px-6 py-3">
                <div class="flex items-center">
                    Log Level:
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

<div class="flex justify-between">
    @if (!$jobLogs->isEmpty())
    <nav class="mt-4">
    <ul class="flex items-center -space-x-px h-10 text-base">
        <li>
            <a onclick="onApplyFilters({{ $jobLogs->currentPage() - 1 }})" class="flex items-center justify-center px-4 h-10 ms-0 hover:cursor-pointer leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Previous</span>
            <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            </a>
        </li>
        @php
            $start = max(1, $jobLogs->currentPage() - 2);
            $end = min($start + 4, $jobLogs->lastPage());
        @endphp
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $jobLogs->currentPage())
            <li>
                <a aria-current="page" class="z-10 flex items-center justify-center px-4 h-10 leading-tight text-blue-600 border border-blue-300 bg-blue-50 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $i }}</a>
            </li>
            @else
                <li>
                    <a onclick="onApplyFilters({{ $i }})" class="flex items-center justify-center px-4 h-10 leading-tight hover:cursor-pointer text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li>
            <a onclick="onApplyFilters({{ $jobLogs->currentPage() + 1 }})" class="flex items-center justify-center px-4 h-10 hover:cursor-pointer leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only">Next</span>
                <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
        </li>
    </ul>
    </nav>
    @endif
    <div class="mt-4">
        <select id="entries" name="entries" class="text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white rounded-lg" onchange="onApplyFilters(1)">
            @foreach ([5, 10, 25, 50, 100] as $entries)
                <option @if ($jobLogs->isNotEmpty() && $jobLogs->count() >= $entries && $jobLogs->count() <= $entries * 2) selected @endif value="{{ $entries }}">{{ $entries }}</option>
            @endforeach
        </select>
    </div>
</div>
