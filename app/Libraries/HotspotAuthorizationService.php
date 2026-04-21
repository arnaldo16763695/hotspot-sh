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
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }

        $schedulerResponse = null;
        $warning = null;

        try {
            $schedulerResponse = $client->put(
                'system/scheduler',
                $this->buildSchedulerPayload($schedulerName, $bindingComment, $config->limitUptime)
            );
        } catch (\Throwable $exception) {
            $warning = 'No se pudo crear el scheduler de limpieza: ' . $exception->getMessage();
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
