<?php

namespace App\Notifications;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCreated extends Notification
{
    use Queueable;

    /**
     * Post of the notification
     */
    private Post $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType()
    {
        return 'post.create';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
     *
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->attributes());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return array_merge([
            "type" => "post.create"
        ], $this->attributes());
    }

    public function attributes()
    {
        return [
            "title" => "New post",
            "text" => $this->post->author->first_name." has created a new post !",
            "image" => $this->post->author->profile_picture,
            "created_at" => Carbon::now(),
            "data" => [
                "post_id" => $this->post->id
            ]
        ];
    }
}
