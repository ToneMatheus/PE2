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
