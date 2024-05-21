<x-app-layout>
<div class="flex flex-col items-center w-full text-gray-900 dark:text-white">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Tickets.TicketOverview') }}
            <a href="{{ route('manager.showTickets') }}" class="ml-4 mt-2 text-sm text-gray-900 dark:text-white">Tickets Escalation Page</a>
        </h2>
    </x-slot>
        <div class="flex justify-center space-x-4 mb-10">
            <div class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-6 rounded-lg shadow-lg w-128 mt-10">
                <h2 class="text-2xl font-bold mb-2 text-center">Average Ticket Closing Time:</h2>
                <p class="text-lg text-center">
                    {{ $averageClosingTime }} hours
                </p>
            </div>
            <div class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-6 rounded-lg shadow-lg w-128 mt-10">
                <h2 class="text-2xl font-bold mb-2 text-center">Average Team Ticket Closing Time:</h2>
                <p class="text-lg text-center">
                    {{ $teamAverageClosingTime }} hours
                </p>
            </div>
        </div>
        <div class="flex justify-center space-x-4">
            <button id="prevMonth" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-2 rounded-lg shadow-lg">&larr; Previous Month</button>
            <span id="currentMonthDisplay" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-2 rounded-lg shadow-lg"></span>
            <button id="nextMonth" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-2 rounded-lg shadow-lg">Next Month &rarr;</button>
        </div>
    <canvas id="myChart" width="800" height="400"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
    
        var dates = <?php echo json_encode($dates->toArray()); ?>;
        var openCounts = <?php echo json_encode($openCounts->toArray()); ?>;
        var closedCounts = <?php echo json_encode($closedCounts->toArray()); ?>;
    
        var openData = dates.map((date) => openCounts[date] || 0);
        var closedData = dates.map((date) => closedCounts[date] || 0);

        var cumulativeCounts = {};
        var cumulativeCount = 0;
        dates.forEach((date, index) => {
            cumulativeCount += openCounts[date] || 0;
            cumulativeCount -= closedCounts[date] || 0;
            cumulativeCounts[date] = cumulativeCount;
        });
        
        var cumulativeData = dates.map((date) => cumulativeCounts[date] || 0);
        
        var months = [...new Set(dates.map(date => new Date(date).getFullYear() * 100 + new Date(date).getMonth()))];
        var currentMonth = Math.max(...months);

    
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates.filter(date => new Date(date).getFullYear() * 100 + new Date(date).getMonth() === currentMonth),
                datasets: [{
                    label: '# of Open Tickets',
                    data: openData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth),
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }, {
                    label: '# of Closed Tickets',
                    data: closedData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth),
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 1
                },{
                    label: 'Cumulative Open Tickets',
                    data: cumulativeData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth),
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    borderColor: 'rgba(0, 255, 0, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'white'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'white'
                        }
                    }
                }
            }
        });
    
        var currentMonthDisplay = document.getElementById('currentMonthDisplay');
    
    function updateChartAndDisplay() {
        myChart.data.labels = dates.filter(date => new Date(date).getFullYear() * 100 + new Date(date).getMonth() === currentMonth);
        myChart.data.datasets[0].data = openData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth);
        myChart.data.datasets[1].data = closedData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth);
        myChart.data.datasets[2].data = cumulativeData.filter((_, index) => new Date(dates[index]).getFullYear() * 100 + new Date(dates[index]).getMonth() === currentMonth);
        myChart.update();
    
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var year = Math.floor(currentMonth / 100);
        var month = currentMonth % 100;
        currentMonthDisplay.textContent = monthNames[month] + " " + year;
    }

    updateChartAndDisplay();

    document.getElementById('prevMonth').addEventListener('click', function() {
        var minMonth = Math.min(...months);
        if (currentMonth > minMonth) {
            currentMonth--;
            updateChartAndDisplay();
        }
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        if (currentMonth < Math.max(...months)) {
            currentMonth++;
            updateChartAndDisplay();
        }
    });
    </script>

<table class="mt-5 border-collapse bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white">
    <thead>
        <tr>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">First Name</th>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">Last Name</th>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">Open tickets</th>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">Closed tickets</th>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">Unsolved tickets</th>
            <th class="p-2 bg-gray-300 dark:bg-gray-800 text-left">Average ticket solving speed</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($teamMembers as $teamMember)
            <tr class="bg-gray-100 dark:bg-gray-600">
                <td class="p-2">{{ $teamMember->first_name }}</td>
                <td class="p-2">{{ $teamMember->last_name }}</td>
                <td class="p-2">{{ $teamMember->tickets[0]['count'] ?? 0 }}</td>
                <td class="p-2">{{ $teamMember->tickets[1]['count'] ?? 0 }}</td>
                <td class="p-2">{{ $teamMember->tickets[1]['unsolved'] ?? 0 }}</td>
                <td class="p-2">{{ $teamMember->averageClosingTime }} hours</td>
            </tr>
        @endforeach
    </tbody>
</table>
</x-app-layout>