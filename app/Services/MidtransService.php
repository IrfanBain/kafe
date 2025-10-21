<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private $serverKey;
    private $isProduction;
    private $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->isProduction = config('services.midtrans.is_production');
        $this->baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function createSnapToken($transactionData)
    {
        try {
            Log::info('Creating Midtrans snap token via custom service', [
                'url' => $this->baseUrl,
                'data' => $transactionData
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])
            ->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 60,
            ])
            ->post($this->baseUrl, $transactionData);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('Snap token created successfully', [
                    'token' => substr($result['token'] ?? 'null', 0, 10) . '...',
                    'redirect_url' => $result['redirect_url'] ?? 'null'
                ]);

                return $result['token'] ?? null;
            } else {
                Log::error('Midtrans API error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

        } catch (\Exception $e) {
            Log::error('Midtrans service error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}