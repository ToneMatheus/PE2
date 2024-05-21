<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketOpenNotification extends Notification
{
    use Queueable;


    protected $ticket;
    protected $roleId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $roleId)
    {
        //
        $this->ticket = $ticket;
        $this->roleId = $roleId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'The ticket #' . $this->ticket->id . ' has been open for a while. Please take a look at it.' . "<br>Issue: " . $this->ticket->issue,
            'ticket_id' => $this->ticket->id,
            'role_id' => $this->roleId,
            'notifiable_id' => $this->roleId,
        ];
    }
}
