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
        $uniqueToken = strtolower(sprintf('%d-%s', $clienteId, date('YmdHis')));
        $bindingComment = sprintf('%s-%s-%s', $config->bindingCommentPrefix, $routerCode, $uniqueToken);
        $schedulerName = sprintf('%s-%s-%s', $config->schedulerPrefix, $routerCode, $uniqueToken);

        if (empty($context['mac_address']) && empty($context['ip_address'])) {
            throw new RuntimeException('No recibimos MAC ni IP del cliente para autorizarlo en el hotspot.');
        }

        log_message('debug', 'Hotspot authorize start cliente={cliente} router={router} mac={mac} ip={ip} hotspot={hotspot}', [
            'cliente' => $clienteId,
            'router' => $routerCode,
            'mac' => $context['mac_address'] ?? '',
            'ip' => $context['ip_address'] ?? '',
            'hotspot' => $context['hotspot_nombre'] ?? '',
        ]);

        $bindingPayload = [
            'type' => 'bypassed',
            'disabled' => 'false',
            'comment' => $bindingComment,
        ];

        if (! empty($context['mac_address'])) {
            $bindingPayload['mac-address'] = $context['mac_address'];
        }

        if (! empty($context['ip_address'])) {
            $bindingPayload['address'] = $context['ip_address'];
        }

        try {
            $bindingResponse = $client->put('ip/hotspot/ip-binding', $bindingPayload);
        } catch (\Throwable $exception) {
            log_message('error', 'Hotspot binding failed cliente={cliente} router={router}: {error}', [
                'cliente' => $clienteId,
                'router' => $routerCode,
                'error' => $exception->getMessage(),
            ]);
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }

        log_message('debug', 'Hotspot binding created cliente={cliente} router={router} comment={comment}', [
            'cliente' => $clienteId,
            'router' => $routerCode,
            'comment' => $bindingComment,
        ]);

        $schedulerResponse = null;
        $warning = null;

        try {
            $schedulerResponse = $client->put(
                'system/scheduler',
                $this->buildSchedulerPayload($schedulerName, $bindingComment, $config->limitUptime)
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
            'binding_comment' => $bindingComment,
            'scheduler_name' => $schedulerName,
            'router_response' => [
                'binding' => $bindingResponse,
                'scheduler' => $schedulerResponse,
            ],
            'warning' => $warning,
        ];
    }

    private function buildSchedulerPayload(string $schedulerName, string $bindingComment, string $limitUptime): array
    {
        $expiresAt = time() + $this->uptimeToSeconds($limitUptime);
        $startDate = strtolower(date('M/d/Y', $expiresAt));
        $startTime = date('H:i:s', $expiresAt);
        $script = sprintf(
            '/ip/hotspot/ip-binding/remove [find where comment="%s"]; /system/scheduler/remove [find where name="%s"]',
            $bindingComment,
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
