<?php

namespace App\Libraries;

use Config\Mikrotik;
use RuntimeException;

class MikrotikRestClient
{
    public function __construct(
        private readonly array $router,
        private readonly ?Mikrotik $config = null,
    ) {
    }

    public function put(string $path, array $payload): array
    {
        return $this->request('PUT', $path, $payload);
    }

    public function post(string $path, array $payload): array
    {
        return $this->request('POST', $path, $payload);
    }

    private function request(string $method, string $path, array $payload = []): array
    {
        $config = $this->config ?? config('Mikrotik');
        $host = rtrim((string) ($this->router['host'] ?? ''), '/');
        $port = (int) ($this->router['puerto'] ?? 443);
        $username = (string) ($this->router['usuario'] ?? '');
        $password = (string) ($this->router['password'] ?? '');

        if ($host === '' || $username === '') {
            throw new RuntimeException('El router no tiene host o usuario configurado.');
        }

        $url = sprintf('https://%s:%d/rest/%s', $host, $port, ltrim($path, '/'));

        $handle = curl_init($url);

        if ($handle === false) {
            throw new RuntimeException('No fue posible iniciar la conexion cURL con MikroTik.');
        }

        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_USERPWD => $username . ':' . $password,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES),
            CURLOPT_CONNECTTIMEOUT => $config->timeoutSeconds,
            CURLOPT_TIMEOUT => $config->timeoutSeconds,
            CURLOPT_SSL_VERIFYPEER => $config->verifyTls,
            CURLOPT_SSL_VERIFYHOST => $config->verifyTls ? 2 : 0,
        ];

        if ($payload !== []) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_UNESCAPED_SLASHES);
        }

        curl_setopt_array($handle, $curlOptions);

        $rawResponse = curl_exec($handle);
        $httpCode = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $curlError = curl_error($handle);
        curl_close($handle);

        if ($rawResponse === false || $curlError !== '') {
            throw new RuntimeException('Fallo de red al conectar con MikroTik: ' . $curlError);
        }

        $decoded = json_decode($rawResponse, true);
        $decoded = is_array($decoded) ? $decoded : [];

        if ($httpCode >= 400) {
            $detail = $decoded['detail'] ?? $decoded['message'] ?? ('HTTP ' . $httpCode);
            throw new RuntimeException('RouterOS REST devolvio un error: ' . $detail);
        }

        return $decoded;
    }
}
