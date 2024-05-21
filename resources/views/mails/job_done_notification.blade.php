@component('mail::message')
# Job Done Notification

Your job "{{ $jobName }}" has been completed successfully.

Thanks,<br>
{{ config('app.name') }}
@endcomponent