<?php

namespace App\Jobs;

use App\Mail\ReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $pdf;
    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_email, $pdf)
    {
        $this->pdf = base64_encode($pdf->output());
        $this->email = $user_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdf = $this->pdf;
        $email = $this->email;
        $data["title"] = "JiDo Report";
        $data["body"] = "Here is your Report";
        Mail::send('emails.taskReport', $data, function($message) use($data, $email, $pdf){
            $message
            ->to($email)
            ->subject($data['title'])
            ->attachData(base64_decode($pdf), 'report.pdf',[
                'mime' => 'application/pdf',
            ]);
        });
    }
}
