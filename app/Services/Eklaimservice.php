<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EklaimService
{
    protected $host;
    protected $key; // hex string dari setup e-Klaim
    protected $debug;

    public function __construct()
    {
        $this->host = config('services.eklaim.host');   // contoh: http://192.168.56.101/E-Klaim/ws.php
        $this->key = config('services.eklaim.key');    // hex string
        $this->debug = filter_var(config('services.eklaim.debug'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Encrypt payload sesuai manual INA-CBG
     */
    private function inacbgEncrypt(string $json): string
    {
        $key = hex2bin($this->key);

        if (strlen($key) !== 32) {
            throw new \Exception("Encryption key harus 256-bit (32 bytes).");
        }

        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        $encrypted = openssl_encrypt($json, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // signature = 10 byte pertama dari HMAC-SHA256
        $signature = substr(hash_hmac('sha256', $encrypted, $key, true), 0, 10);

        // gabungkan (signature + iv + ciphertext)
        $data = base64_encode($signature . $iv . $encrypted);

        // format dengan chunk_split (seperti manual)
        return "----BEGIN ENCRYPTED DATA----\n" .
            chunk_split($data) .
            "----END ENCRYPTED DATA----";
    }

    /**
     * Decrypt response sesuai manual INA-CBG
     */
    private function inacbgDecrypt(string $payload): array
    {
        $key = hex2bin($this->key);

        if (strlen($key) !== 32) {
            throw new \Exception("Encryption key harus 256-bit (32 bytes).");
        }

        // hapus header/footer BEGIN/END
        $payload = preg_replace("/-----(BEGIN|END) ENCRYPTED DATA-----/", "", $payload);
        $payload = trim($payload);

        $decoded = base64_decode($payload);

        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $signature = substr($decoded, 0, 10);
        $iv = substr($decoded, 10, $iv_size);
        $encrypted = substr($decoded, 10 + $iv_size);

        // verifikasi signature
        $calc_signature = substr(hash_hmac('sha256', $encrypted, $key, true), 0, 10);
        if ($signature !== $calc_signature) {
            throw new \Exception("SIGNATURE_NOT_MATCH");
        }

        $json = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return json_decode($json, true) ?? [];
    }

    /**
     * Kirim request ke E-Klaim WS
     * 
     * @param string $method  nama method web service (mis: new_claim, delete_patient, dll)
     * @param array $data     isi data
     * @param array $extraMetadata tambahan metadata (opsional)
     */
    public function send(string $method, array $data = [], array $extraMetadata = [])
    {
        // Bentuk payload sesuai format e-Klaim
        $payload = [
            'metadata' => array_merge(['method' => $method], $extraMetadata),
            'data' => $data,
        ];

        // Tentukan URL (otomatis tambah ?mode=debug kalau debug aktif)
        $url = $this->host . ($this->debug ? '?mode=debug' : '');

        // ===============================
        // MODE DEBUG (kirim JSON murni)
        // ===============================
        if ($this->debug) {
            $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

            $response = Http::withoutRedirecting()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->withOptions([
                    'verify' => false, // skip SSL verification (untuk lokal)
                    'http_errors' => false, // jangan lempar exception otomatis
                ])
                ->send('POST', $url, [
                    'body' => $json, // kirim JSON murni seperti cURL
                ]);

            // Log untuk debugging (cek hasil HTML/JSON mentah)
            \Log::info('E-KLAIM DEBUG RESPONSE', [
                'url' => $url,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);

            // Coba parse JSON, kalau gagal kirim raw
            $jsonDecoded = json_decode($response->body(), true);
            return $jsonDecoded ?? ['raw' => $response->body(), 'status' => $response->status()];
        }

        // ===============================
        // MODE PRODUKSI (payload terenkripsi)
        // ===============================
        $encrypted = $this->inacbgEncrypt(json_encode($payload, JSON_UNESCAPED_UNICODE));

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->withOptions([
                    'verify' => false,
                    'http_errors' => false,
                ])->send('POST', $url, [
                    'body' => $encrypted,
                ]);

        $raw = $response->body();

        try {
            return $this->inacbgDecrypt($raw);
        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Decrypt failed: ' . $e->getMessage(),
                'raw' => $raw,
            ];
        }
    }



}
