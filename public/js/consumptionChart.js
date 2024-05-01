var consumptionChart;

window.createChart = function() {
    const ctx = document.getElementById('consumptionChart').getContext('2d');
    window.consumptionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Energy Consumption',
                data: [],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });
};

window.fetchData = function(timeframe) {
    $.ajax({
        url: 'Meters\Meter_History' + timeframe,
        method: 'GET',
        success: function(response) {
            var consumptionData = response.consumptionData;
            window.updateChart(consumptionData, timeframe);
        }
    });
};

$(document).ready(function() {
    window.createChart();
    window.fetchData('month');
});

function calculateAverage(consumptionData, timeframe) {
    let groupedData = {};
    consumptionData.forEach(data => {
        let date = new Date(data.reading_date);
        let key = timeframe === 'year' ? `${date.getFullYear()}-${date.getMonth() + 1}` : data.reading_date;
        if (!groupedData[key]) {
            groupedData[key] = [];
        }
        groupedData[key].push(data.reading_value);
    });

    let averagedData = Object.keys(groupedData).map(date => {
        let values = groupedData[date];
        let average = values.length > 1 ? values.reduce((a, b) => a + b, 0) / values.length : values[0];
        return { reading_date: date, reading_value: average };
    });

    averagedData.sort((a, b) => new Date(a.reading_date) - new Date(b.reading_date));

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    switch(timeframe) {
        case 'week':
            return averagedData.map((data, index) => {
                data.reading_date = days[new Date(data.reading_date).getDay()];
                return data;
            });
        case 'month':
            let monthlyResult = [];
            let startOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
            let endOfMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
            let startOfWeek = startOfMonth;
            while (startOfWeek <= endOfMonth) {
                let endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(endOfWeek.getDate() + 6);
                if (endOfWeek > endOfMonth) {
                    endOfWeek = endOfMonth;
                }
                let weekData = averagedData.filter(data => new Date(data.reading_date) >= startOfWeek && new Date(data.reading_date) <= endOfWeek);
                let average = weekData.reduce((a, b) => a + b.reading_value, 0) / weekData.length;
                monthlyResult.push({ reading_date: `Week Starting ${startOfWeek.toISOString().split('T')[0]}`, reading_value: average });
                startOfWeek.setDate(startOfWeek.getDate() + 7);
            }
            return monthlyResult;
        case 'year':
            return averagedData.map((data, index) => {
                let date = new Date(data.reading_date);
                data.reading_date = months[date.getMonth()];
                return data;
            });
        default:
            return consumptionData;
    }
}

function updateChart(consumptionData, timeframe) {
    const averageConsumption = calculateAverage(consumptionData, timeframe);

    consumptionChart.data.labels = averageConsumption.map(data => data.reading_date);
    consumptionChart.data.datasets[0].data = averageConsumption.map(data => data.reading_value);

    consumptionChart.update();
}

window.updateChart = updateChart;
