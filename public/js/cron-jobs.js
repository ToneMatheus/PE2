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

async function showHistory(cronJob) {
    const historyResponse = await fetch(`/cron-jobs/${cronJob}/history/1`);
    const historyHtml = await historyResponse.text();

    // Display history data in modal
    const historyModal = document.querySelector('.history-modal');
    historyModal.querySelector('.history-content').innerHTML = historyHtml;
    historyModal.classList.remove('hidden');
}

async function showHistoryPage(cronJob, page){
    const historyResponse = await fetch(`/cron-jobs/${cronJob}/history/${page}`);
    const historyHtml = await historyResponse.text();

    // Display history data in modal
    document.querySelector('.history-modal').querySelector('.history-content').innerHTML = historyHtml;
}

function closeHistoryModal() {
    document.querySelector('.history-modal').classList.add('hidden');
}
