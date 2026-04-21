<?php

namespace App\Libraries;

use Config\Mikrotik;
use RuntimeException;

class HotspotAuthorizationService
{
    public function __construct(private readonly ?Mikrotik $config = null)
    {
    }

    public function authorize(array $router, array $context, int $clienteId, string $celular): array
    {
        $config = $this->config ?? config('Mikrotik');
        $client = new MikrotikRestClient($router, $config);

        $routerCode = (string) ($router['codigo'] ?? $context['router_code'] ?? 'router');
        $username = strtolower(sprintf(
            '%s-%d-%s',
            $config->hotspotUserPrefix,
            $clienteId,
            date('YmdHis')
        ));
        $password = bin2hex(random_bytes(6));

        $payload = [
            'name' => $username,
            'password' => $password,
            'profile' => $config->hotspotUserProfile,
            'limit-uptime' => $config->limitUptime,
            'comment' => sprintf('Portal hotspot cliente %d celular %s router %s', $clienteId, $celular, $routerCode),
        ];

        if (! empty($context['hotspot_nombre'])) {
            $payload['server'] = $context['hotspot_nombre'];
        }

        if (! empty($context['mac_address'])) {
            $payload['mac-address'] = $context['mac_address'];
        }

        try {
            $response = $client->put('ip/hotspot/user', $payload);
        } catch (\Throwable $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }

        return [
            'username' => $username,
            'password' => $password,
            'router_response' => $response,
            'login_url' => $context['link_login_only'] ?? null,
            'dst' => $context['link_orig'] ?? null,
        ];
    }
}
