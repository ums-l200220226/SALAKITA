<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class XenditService
{
    protected $secretKey;
    protected $baseUrl;
    protected $isSandbox;

    public function __construct()
    {
        $this->secretKey = config('xendit.secret_key');
        $this->baseUrl = config('xendit.base_url');
        $this->isSandbox = config('xendit.is_sandbox');

        if (empty($this->secretKey)) {
            throw new Exception('Xendit API key tidak ditemukan. Cek file .env');
        }
    }

    public function createQRIS($order)
    {
        try {
            // Smart callback URL - otomatis pakai dummy kalau localhost
            $callbackUrl = str_contains(config('app.url'), 'localhost')
                ? 'https://webhook.site/' . md5($order->id)
                : route('xendit.callback');

            $data = [
                'external_id' => 'order-' . $order->id . '-' . time(),
                'type' => 'DYNAMIC',
                'callback_url' => $callbackUrl,
                'amount' => (int) $order->total,
            ];

            Log::info('Creating QRIS', $data);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/qr_codes', $data);

            if ($response->failed()) {
                Log::error('Xendit QRIS Failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Gagal membuat QRIS: ' . $response->body());
            }

            $result = $response->json();
            Log::info('QRIS Created Successfully', $result);

            return $result;

        } catch (Exception $e) {
            Log::error('Xendit Exception', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getQRISStatus($qrId)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                ])->get($this->baseUrl . '/qr_codes/' . $qrId);

            if ($response->failed()) {
                throw new Exception('Gagal mengecek status QRIS');
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('Get QRIS Status Exception', ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
