<?php

namespace App\Notifications;

use App\InterviewSchedule;
use App\JobApplication;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CandidateStatusChange extends Notification
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(JobApplication $jobApplication)
    {
        $this->jobApplication = $jobApplication;
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject(__('email.candidateStatusUpdate.subject'))
            ->greeting(__('email.hello').' ' . ucwords($notifiable->full_name) . '!')
            ->line(__('email.candidateStatusUpdate.text').' - ' . ucwords($this->jobApplication->job->title))
            ->line(__('email.ScheduleStatusCandidate.nowStatus').' - ' . ucfirst($this->jobApplication->status->status))
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
       //
    }

}