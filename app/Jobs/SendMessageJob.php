<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $phone;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $message = null)
    {
        $this->phone = $phone;
        $this->message = $message ?? '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = env('WA_BLAS_TOKEN');
        $secretKey = env('WA_BLAS_SECRET');

        $response = Http::withHeaders([
            'Authorization' => "$token.$secretKey",
        ])->post('https://kudus.wablas.com/api/send-message', [
            'phone' => $this->phone,
            'message' => $this->message,
        ]);

        if (!$response->successful()) {
            Log::error('Gagal mengirim pesan.', [
                'phone' => $this->phone,
                'message' => $this->message,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
