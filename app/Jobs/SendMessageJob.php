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
    protected $jenis;
    protected $phone;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $message = null, $jenis = "text")
    {
        $this->jenis = $jenis ?? 'text';
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
        if ($this->jenis == "text") {
            $this->sendTextMessage($token, $secretKey);
        } elseif ($this->jenis == "document") {
            $this->sendDocumentMessage($token, $secretKey);
        }
    }

    /**
     * Mengirim pesan teks ke WhatsApp API.
     */
    private function sendTextMessage($token, $secretKey)
    {
        $response = Http::withHeaders([
            'Authorization' => "$token.$secretKey",
        ])->post('https://kudus.wablas.com/api/send-message', [
            'phone' => $this->phone,
            'message'  => $this->message,
        ]);
        $this->handleResponse($response);
    }

    /**
     * Mengirim pesan dokumen jika ada link dalam pesan.
     */
    private function sendDocumentMessage($token, $secretKey)
    {
        $dokumen = $this->extractLinkDetails($this->message);
        // dd($dokumen);
        if ($dokumen && isset($dokumen['document'])) {
            $response = Http::withHeaders([
                'Authorization' => "$token.$secretKey",
            ])->post('https://kudus.wablas.com/api/send-document', [
                'phone' => $this->phone,
                'document' => $dokumen['document'],
                'caption' => $dokumen['caption'],
            ]);

            $this->handleResponse($response);
        } else {
            Log::warning('Pesan dokumen tidak valid.', [
                'phone' => $this->phone,
                'message' => $this->message,
            ]);
        }
    }

    /**
     * Mengekstrak link dan caption dari pesan HTML.
     */
    private function extractLinkDetails($htmlString)
    {
        $pattern = '/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i';
        if (preg_match($pattern, $htmlString, $matches)) {
            return [
                'document' => $matches[1] ?? null,
                'caption' => trim(strip_tags($matches[2])) ?? null
            ];
        }
        return null;
    }

    /**
     * Menangani respons dari API dan logging jika gagal.
     */
    private function handleResponse($response)
    {
        if (!$response->successful()) {
            Log::error('Gagal mengirim pesan.', [
                'phone' => $this->phone,
                'jenis' => $this->jenis,
                'message' => $this->message,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
