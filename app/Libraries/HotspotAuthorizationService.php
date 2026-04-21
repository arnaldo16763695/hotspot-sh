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
        $hotspotUser = $celular;
        $hotspotPassword = $celular;
        $schedulerName = sprintf('%s-%s-%s', $config->schedulerPrefix, $routerCode, $hotspotUser);

        log_message('debug', 'Hotspot authorize start cliente={cliente} router={router} mac={mac} ip={ip} hotspot={hotspot}', [
            'cliente' => $clienteId,
            'router' => $routerCode,
            'mac' => $context['mac_address'] ?? '',
            'ip' => $context['ip_address'] ?? '',
            'hotspot' => $context['hotspot_nombre'] ?? '',
        ]);

        if (empty($context['ip_address'])) {
            log_message('error', 'Hotspot authorize missing client ip cliente={cliente} router={router} mac={mac} hotspot={hotspot}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'mac' => $context['mac_address'] ?? '',
                'hotspot' => $context['hotspot_nombre'] ?? '',
            ]);
            throw new RuntimeException('No recibimos la IP del cliente desde MikroTik, por lo que no podemos loguearlo en el hotspot.');
        }

        if (empty($context['mac_address']) && empty($context['ip_address'])) {
            log_message('error', 'Hotspot authorize missing client identity cliente={cliente} router={router} hotspot={hotspot}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'hotspot' => $context['hotspot_nombre'] ?? '',
            ]);
            throw new RuntimeException('No recibimos MAC ni IP del cliente para autorizarlo en el hotspot.');
        }

        $userPayload = [
            'name' => $hotspotUser,
            'password' => $hotspotPassword,
            'profile' => $config->hotspotUserProfile,
            'disabled' => 'false',
            'limit-uptime' => $config->limitUptime,
            'comment' => sprintf('Portal hotspot cliente %d celular %s router %s', $clienteId, $celular, $routerCode),
        ];

        if (! empty($context['hotspot_nombre'])) {
            $userPayload['server'] = $context['hotspot_nombre'];
        }

        try {
            $existingUsers = $client->get('ip/hotspot/user', ['name' => $hotspotUser]);
            $existingUsers = isset($existingUsers[0]) ? $existingUsers : [];
        } catch (\Throwable $exception) {
            throw new RuntimeException('No pudimos consultar el usuario hotspot existente: ' . $exception->getMessage(), 0, $exception);
        }

        try {
            if ($existingUsers !== []) {
                $userId = $existingUsers[0]['.id'] ?? null;
                if ($userId === null) {
                    throw new RuntimeException('El usuario hotspot existente no devolvio .id.');
                }

                $userResponse = $client->patch('ip/hotspot/user/' . rawurlencode($userId), $userPayload);
            } else {
                $userResponse = $client->put('ip/hotspot/user', $userPayload);
            }
        } catch (\Throwable $exception) {
            log_message('error', 'Hotspot user upsert failed cliente={cliente} router={router}: {error}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'error' => $exception->getMessage(),
            ]);
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }

        log_message('debug', 'Hotspot user ready cliente={cliente} router={router} user={user}', [
            'cliente' => $clienteId,
            'router' => $routerCode,
            'user' => $hotspotUser,
        ]);

        try {
            $existingActiveSessions = $client->get('ip/hotspot/active', [
                '.query' => sprintf('user=%s', $hotspotUser),
            ]);

            foreach ($existingActiveSessions as $activeSession) {
                if (! empty($activeSession['.id'])) {
                    $client->delete('ip/hotspot/active/' . rawurlencode((string) $activeSession['.id']));
                }
            }

            $loginPayload = [
                'user' => $hotspotUser,
                'password' => $hotspotPassword,
                'ip' => $context['ip_address'],
            ];

            if (! empty($context['mac_address'])) {
                $loginPayload['mac-address'] = $context['mac_address'];
            }

            $activeLoginResponse = $client->post('ip/hotspot/active/login', $loginPayload);
        } catch (\Throwable $exception) {
            log_message('error', 'Hotspot active login failed cliente={cliente} router={router}: {error}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'error' => $exception->getMessage(),
            ]);
            throw new RuntimeException('No pudimos loguear al cliente en el hotspot: ' . $exception->getMessage(), 0, $exception);
        }

        $schedulerResponse = null;
        $warning = null;

        try {
            $existingSchedulers = $client->get('system/scheduler', ['name' => $schedulerName]);
            if (isset($existingSchedulers[0]['.id'])) {
                $client->delete('system/scheduler/' . rawurlencode($existingSchedulers[0]['.id']));
            }

            $schedulerResponse = $client->put(
                'system/scheduler',
                $this->buildSchedulerPayload($schedulerName, $hotspotUser, $config->limitUptime)
            );
        } catch (\Throwable $exception) {
            $warning = 'No se pudo crear el scheduler de limpieza: ' . $exception->getMessage();
            log_message('warning', 'Hotspot scheduler failed cliente={cliente} router={router} scheduler={scheduler}: {error}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'scheduler' => $schedulerName,
                'error' => $exception->getMessage(),
            ]);
        }

        if ($schedulerResponse !== null) {
            log_message('debug', 'Hotspot scheduler created cliente={cliente} router={router} scheduler={scheduler}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'scheduler' => $schedulerName,
            ]);
        }

        return [
            'hotspot_user' => $hotspotUser,
            'scheduler_name' => $schedulerName,
            'router_response' => [
                'user' => $userResponse,
                'active_login' => $activeLoginResponse,
                'scheduler' => $schedulerResponse,
            ],
            'warning' => $warning,
        ];
    }

    private function buildSchedulerPayload(string $schedulerName, string $hotspotUser, string $limitUptime): array
    {
        $expiresAt = time() + $this->uptimeToSeconds($limitUptime);
        $startDate = strtolower(date('M/d/Y', $expiresAt));
        $startTime = date('H:i:s', $expiresAt);
        $script = sprintf(
            '/ip/hotspot/active/remove [find where user="%s"]; /ip/hotspot/user/remove [find where name="%s"]; /system/scheduler/remove [find where name="%s"]',
            $hotspotUser,
            $hotspotUser,
            $schedulerName
        );

        return [
            'name' => $schedulerName,
            'disabled' => 'false',
            'start-date' => $startDate,
            'start-time' => $startTime,
            'on-event' => $script,
        ];
    }

    private function uptimeToSeconds(string $value): int
    {
        if (preg_match('/^(\d+)h$/', $value, $matches) === 1) {
            return (int) $matches[1] * 3600;
        }

        if (preg_match('/^(\d+)m$/', $value, $matches) === 1) {
            return (int) $matches[1] * 60;
        }

        if (preg_match('/^(\d+)s$/', $value, $matches) === 1) {
            return (int) $matches[1];
        }

        throw new RuntimeException('Formato de limitUptime no soportado: ' . $value);
    }
}
