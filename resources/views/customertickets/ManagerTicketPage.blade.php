<x-app-layout>
<div class="flex flex-col items-center w-full text-white">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Tickets.TicketOverview') }}
        </h2>
    </x-slot> 
    <div class="flex space-x-4 mt-4">
        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="window.location.href='?status=open'">Open</button>
        <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" onclick="window.location.href='?status=closed'">Closed</button>
        <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="window.location.href='?status=all'">All</button>
    </div>
    <canvas id="myChart" width="800" height="400"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates->toArray()); ?>,
                datasets: [{
                    label: '# of Tickets',
                    data: <?php echo json_encode($counts->toArray()); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
    </script>
</x-app-layout>