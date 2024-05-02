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

function onApplyFilters(page = 1){
    var JobRunId = document.getElementById('JobRun').value;
    var LogLevel = document.getElementById('LogLevel').value;
    var Entries = document.getElementById('entries').value;

    // Send AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/cron-jobs/get-job-run-logs?jobRunId=' + JobRunId + "&LogLevel=" + LogLevel + "&page=" + page + "&entries=" + Entries, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var newTableHTML = xhr.responseText;

            var existingTable = document.getElementById('Logs');
            existingTable.innerHTML = newTableHTML;
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.send();
}

function showModal(jobName) {
    document.getElementById('log_level_modal').setAttribute('data-job-name', jobName);
    document.getElementById('log_level_modal').classList.remove('hidden');
    document.getElementById('modal-backdrop').classList.remove('hidden');
}

function hideModal() {
    const modal = document.getElementById('log_level_modal');
    const modalBackdrop = document.getElementById('modal-backdrop');
    modal.classList.add('hidden');
    modalBackdrop.classList.add('hidden');
}

function runJob() {
    var jobName = document.getElementById('log_level_modal').getAttribute('data-job-name');
    var LogLevel = document.getElementById('LogLevel').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/cron-jobs/run/' + jobName + "/" + LogLevel);
    var csrf = document.querySelector('meta[name="csrf-token"]').content;
    xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Request succesful. Status: ' + xhr.status);
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.send();
}