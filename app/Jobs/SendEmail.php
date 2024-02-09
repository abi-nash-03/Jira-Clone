<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $task, $msg, $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $task, $msg, $file)
    {
        $this->user = $user;
        $this->task = $task;
        $this->msg = $msg;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = $this->task;
        $user = $this->user;
        $file = $this->file;
        Mail::send($file, $task, 
        function($message) use ($user) {
            $msg = $this->msg;
            $message
            ->to($user['email'], $user['name'])
            ->subject($msg);
        });
    }
}
