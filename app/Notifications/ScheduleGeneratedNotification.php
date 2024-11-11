<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ScheduleGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;

    /**
     * Create a new notification instance.
     *
     * @param string $status - 'success' or 'warning'
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Send via email
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->status === 'success') {
            return (new MailMessage)
                        ->subject('Thời khóa biểu đã được tạo thành công')
                        ->line('Thời khóa biểu của bạn đã được tạo tự động thành công.')
                        ->action('Xem Thời khóa biểu', route('pdt.schedules.index'))
                        ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
        } elseif ($this->status === 'warning') {
            return (new MailMessage)
                        ->subject('Thời khóa biểu tạo thành công với một số cảnh báo')
                        ->line('Thời khóa biểu của bạn đã được tạo tự động, nhưng một số môn học chưa được lên lịch đủ buổi.')
                        ->action('Xem Thời khóa biểu', route('pdt.schedules.index'))
                        ->line('Vui lòng kiểm tra và điều chỉnh nếu cần thiết.');
        }
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
            //
        ];
    }
}
