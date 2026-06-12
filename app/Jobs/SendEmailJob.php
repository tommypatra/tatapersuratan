<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $subject;
    protected $message;

    public function __construct($email, $subject, $message)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function handle()
    {
        try {
            Mail::raw($this->message, function ($mail) {
                $mail->to($this->email)
                     ->subject($this->subject);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email', [
                'email' => $this->email,
                'error' => $e->getMessage()
            ]);
        }
    }
}