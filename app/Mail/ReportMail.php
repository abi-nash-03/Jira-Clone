<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pdf, $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $pdf)
    {
        $this->pdf = $pdf;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.taskReport')
        ->attachData($this->pdf, 'report.pdf')
        ->from($this->email);

    }
}
