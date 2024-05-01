<x-app-layout>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/manager.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Manager page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color: #D6D5C9">
    
    <h1 style="text-align: center; margin-top: 20px" class="h1">This week's activity</h1><br/>

    <div class="requests" style="margin: auto">
        <div>
        <h4 style="margin-left: 20px" class="h4">My team</h4>
        </div>

        <div style="margin-bottom: 60px;" class="c">
            <div style="display: flex; justify-content: space-between">
                <div class="col-4">
                    <table class="employee-table">
                        <th>EmpID</th>
                        <th>Full name</th>
                        <th>Work done %</th>
                        <th>Tasks completed</th>
                        <th>Message</th>
                        <tr>
                            <td>1</td>
                            <td>John</td>
                            <td>Doe</td>
                            <td>john@example.com</td>
                            <td style="text-align: center"><a href="#"><button class="btn btn-primary">View</button></a></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </table>
                </div>
    
                <div class="container">
                    <h4>Team status</h4>
                    <div id="chart">
                        <canvas id="employeePieChart" width="270" height="270"></canvas>
                    </div>
                </div>
    
            </div>
            </div>
        </div>

        <div style="margin: auto">
            <h2 style="text-align: center; margin-bottom: 20px" class="h4">Task history</h2>

            <table>
                <th>#</th>
                <th>Emp name</th>
                <th>Start date</th>
                <th>Task name</th>
                <th>Description</th>
                <th>Status</th>

                <tr>
                    <td>1</td>
                    <td>2024-04-01</td>
                    <td>John Doe</td>
                    <td>2024-04-10</td>
                    <td>2024-04-15</td>
                    <td><span class="badge badge-success">Completed</span></td>
                </tr>
                <!-- Add more rows as needed -->
            </table>
        </div>
    </div> 

    <script>
        // Function to dynamically update the pie chart with percentages
function updatePieChart() {
    // Static data for the pie chart
    var employees = [
        { name: "Work done", attendance: 3 },
        { name: "Work left", attendance: 4 }
    ];

    var totalAttendance = employees.reduce((total, employee) => total + employee.attendance, 0);

    var labels = [];
    var data = [];
    employees.forEach(function(employee) {
        labels.push(employee.name);
        var percentage = (employee.attendance / totalAttendance) * 100;
        data.push(percentage.toFixed(2)); // Round to 2 decimal places
    });

    // Get the canvas element
    var ctx = document.getElementById('employeePieChart').getContext('2d');

    // Create the pie chart
    var employeePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Percentage',
                data: data,
                backgroundColor: [
                    'red',
                    'blue',
                ],
                borderColor: [
                    'red',
                    'blue',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = ((currentValue / total) * 100).toFixed(2);
                        return percentage + "%";
                    }
                }
            }
        }
    });
}


        // Call the function to initially render the pie chart
        updatePieChart();
    </script>
</body>
</html>
</x-app-layout>