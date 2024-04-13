function showFields() {
    const intervalSelect = document.getElementById('interval');

    if (intervalSelect.value === 'none'){
        scheduled_month_field.classList.add('hidden');
        scheduled_day_field.classList.add('hidden');
        scheduled_time_field.classList.add('hidden');
    } 
    else if (intervalSelect.value === 'yearly') {
        scheduled_month_field.classList.remove('hidden');
        scheduled_day_field.classList.remove('hidden');
        scheduled_time_field.classList.remove('hidden');
    } 
    else if (intervalSelect.value === 'monthly') {
        scheduled_month_field.classList.add('hidden');
        scheduled_day_field.classList.remove('hidden');
        scheduled_time_field.classList.remove('hidden');
    } 
    else if (intervalSelect.value === 'daily') {
        scheduled_month_field.classList.add('hidden');
        scheduled_day_field.classList.add('hidden');
        scheduled_time_field.classList.remove('hidden');
    }
}

function OnchangeJob(){
    var jobElem = document.getElementById('Job').value;

    // Send AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/cron-jobs/get-job-runs?job=' + jobElem, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // If request is successful, update JobRun select options
            var jobRuns = JSON.parse(xhr.responseText);
            var jobRunSelect = document.getElementById('JobRun');
            jobRunSelect.innerHTML = '';
            
            if (jobRuns.length === 0) {
                var defaultOption = document.createElement('option');
                defaultOption.textContent = 'No runs available';
                jobRunSelect.disabled = true;
                defaultOption.selected = true;
                
                jobRunSelect.appendChild(defaultOption);
            
            } else {
                jobRunSelect.disabled = false;

                var totalRuns = jobRuns.length;
                jobRuns.reverse().forEach(function (jobRun, index) {
                    var option = document.createElement('option');
                    option.value = jobRun.id;
                    var runNumber = totalRuns - index;
                    option.textContent = 'Run: ' + runNumber + ' - ' + jobRun.started_at;
                    jobRunSelect.appendChild(option);
                });
            }
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.send();
}

function onApplyFilters(){
    var JobRunId = document.getElementById('JobRun').value;
    var LogLevel = document.getElementById('LogLevel').value;

    // Send AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/cron-jobs/get-job-run-logs?jobRunId=' + JobRunId + "&LogLevel=" + LogLevel, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var newTableHTML = xhr.responseText;

            var existingTable = document.getElementById('LogsTable');
            existingTable.innerHTML = newTableHTML;
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.send();
}