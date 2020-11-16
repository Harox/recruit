<?php

namespace App\Notifications;

use App\InterviewSchedule;
use App\JobApplication;
use App\SmsSetting;
use App\Traits\SmsSettings;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class EmployeeResponse extends Notification
{
    use Queueable, SmtpSettings, SmsSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(InterviewSchedule $schedule, $type, $userData)
    {
        $this->schedule = $schedule;
        $this->type = $type;
        $this->userData = $userData;
        $this->smsSetting = SmsSetting::first();

        $this->setMailConfigs();
        $this->setSmsConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['mail', 'database'];

        if ($this->smsSetting->nexmo_status == 'active' && $notifiable->mobile_verified == 1) {
            array_push($via, 'nexmo');   
        }

        return $via;
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
            ->subject(__('email.interviewSchedule.scheduleResponse'))
            ->greeting(__('email.hello').' ' . ucwords($notifiable->name) . '!')
            ->line(ucwords($this->userData->name).' '.__('email.interviewSchedule.employeeResponse' , ['type' => ucfirst($this->type),'job' => ucwords($this->schedule->jobApplication->job->title)]).' ' .ucwords($this->schedule->jobApplication->full_name))
            ->line(__('email.interviewOn').' - ' . $this->schedule->schedule_date->format('M d, Y h:i a'))
            ->line(__('email.thankyouNote'));
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
            'data' => $notifiable->toArray()
        ];
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
                    ->content(
                        ucwords($this->userData->name).'has '.ucfirst($this->type).' '.ucwords($this->schedule->jobApplication->full_name).'\'s application for '.ucwords($this->schedule->jobApplication->job->title).' on '.$this->schedule->schedule_date->format('M d, Y h:i a')
                    )->unicode();
    }
}
