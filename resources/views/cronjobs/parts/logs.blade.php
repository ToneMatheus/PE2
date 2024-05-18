<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
        @if(isset($jobRun))
        {{$jobRun->name}} Logs
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                <span>Status:</span>
                <span class="{{$jobRun->status === 'Failed' ? 'text-red-600' : 'text-green-600'}}">{{$jobRun->status}}</span>
                <span class="block">Started at: {{ \Carbon\Carbon::parse($jobRun->started_at)->format('d-m-Y H:i') }}</span>

                <span>Message:</span>
                <span class="{{$jobRun->status === 'Failed' ? 'text-red-600' : 'text-green-600'}}">{{$jobRun->error_message}}</span>
            </p>
        @endif
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
            <span>Statistics:</span>
            @foreach($logCounts as $logCount)
                {{ ucfirst($logCount->log_level) }}: {{ $logCount->count }}
            @endforeach
        </p>
    </caption>
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">Log Level:</th>
            <th scope="col" class="px-6 py-3">Message:</th>
            <th scope="col" class="px-6 py-3"></th>
        </tr>
    </thead>
    <tbody>
        @if (!$jobLogs->isEmpty())
        @foreach ($jobLogs as $jobLog)
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $jobLog->log_level }}
            </td>
            <td class="px-6 py-4">
                {{ $jobLog->message }}
            </td>
            <td class="px-4 py-4 cursor-pointer" onclick="toggleDetails(this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </td>
        </tr>
        <tr class="hidden bg-gray-100 dark:bg-gray-700">
            <td colspan="3" class="px-4 py-2">
                <p><strong>Invoice ID:</strong> 
                @if ($jobLog->invoice_id != null)
                    {{ $jobLog->invoice_id }}
                @else
                    N/A
                @endif
                </p>
                <p>
                    <strong>Logged by:</strong> 
                    {{ $jobLog->job_name }}
                </p>
                @if ($jobLog->detailed_message != null)
                    <p>
                        <strong>Detailed Message:</strong> 
                        {{ $jobLog->detailed_message }}
                    </p>
                @endif
                
            </td>
        </tr>
        @endforeach
        @else
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td colspan="4" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
