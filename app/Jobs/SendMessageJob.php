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
        $url = env('WA_BLAS_URL');

        $this->sendTextMessage(
            $token,
            $secretKey,
            $url
        );
    }

    private function sendTextMessage($token, $secretKey, $url)
    {
        $curl = curl_init();

        $data = [
            'phone'   => $this->phone,
            'message' => $this->message,
            'flag'    => 'instant',
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: {$token}.{$secretKey}",
        ]);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt(
            $curl,
            CURLOPT_URL,
            rtrim($url, '/') . '/api/send-message'
        );

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('WABLAS CURL ERROR', [
                'phone' => $this->phone,
                'error' => curl_error($curl),
            ]);
        }

        Log::info('WABLAS RESPONSE', [
            'phone' => $this->phone,
            'result' => $result,
        ]);

        curl_close($curl);
    }
}
