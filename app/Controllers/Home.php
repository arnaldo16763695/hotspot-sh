<?php

namespace App\Controllers;

use App\Libraries\HotspotAuthorizationService;
use App\Models\ClienteModel;
use App\Models\EventoAccesoModel;
use App\Models\MikrotikRouterModel;
use App\Models\SesionHotspotModel;
use App\Models\SucursalModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    private const DURACION_ACCESO_MINUTOS = 60;
    private const VENTANA_REINGRESO_HORAS = 3;

    public function adminLogin(): string
    {
        return view('admin/login');
    }

    public function index(): string
    {
        $oldInput = session()->getFlashdata('old');
        $registrationContext = session()->getFlashdata('registrationContext');

        $oldInput = is_array($oldInput) ? $oldInput : [];
        $registrationContext = is_array($registrationContext) ? $registrationContext : [];

        $fallbackContext = array_merge($registrationContext, $oldInput);
        $context = $this->resolveConnectionContext($fallbackContext);

        $viewData = [
            'errors' => session()->getFlashdata('errors') ?? [],
            'old' => $oldInput,
            'mode' => session()->getFlashdata('mode') ?? 'identify',
            'status' => session()->getFlashdata('status'),
            'waitUntil' => session()->getFlashdata('waitUntil'),
            'registrationContext' => $registrationContext !== [] ? $registrationContext : $context,
            'branchInfo' => $context,
        ];

        if (! empty($context['context_error']) && empty($viewData['status'])) {
            $viewData['status'] = $context['context_error'];
        }

        return view('portal/index', $viewData);
    }

    public function identify()
    {
        $celular = $this->normalizePhone((string) $this->request->getPost('celular'));
        $context = $this->resolveConnectionContext();

        if (! empty($context['context_error'])) {
            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'identify')
                ->with('status', $context['context_error'])
                ->with('old', array_merge($context, ['celular' => (string) $this->request->getPost('celular')]));
        }

        if ($celular === '') {
            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'identify')
                ->with('errors', ['celular' => 'Ingresa un numero de celular valido.'])
                ->with('old', array_merge($context, ['celular' => (string) $this->request->getPost('celular')]));
        }

        $clienteModel = new ClienteModel();
        $eventoModel = new EventoAccesoModel();
        $sesionModel = new SesionHotspotModel();

        $cliente = $clienteModel->where('celular', $celular)->first();

        $eventoModel->registrar(
            $cliente['id'] ?? null,
            'INTENTO_IDENTIFICACION',
            'Intento de identificacion con celular ' . $celular,
            $context
        );

        if ($cliente === null) {
            $eventoModel->registrar(null, 'USUARIO_NO_EXISTE', 'Celular no registrado: ' . $celular, $context);

            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'register')
                ->with('old', array_merge($context, ['celular' => $celular]))
                ->with('registrationContext', $context)
                ->with('status', 'Completa tus datos para activar tu acceso gratuito.');
        }

        if ($this->profileNeedsCompletion($cliente)) {
            $eventoModel->registrar((int) $cliente['id'], 'DATOS_INCOMPLETOS', 'Cliente existente con perfil incompleto.', $context);

            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'register')
                ->with('old', array_merge($context, $cliente))
                ->with('registrationContext', $context)
                ->with('status', 'Necesitamos completar tus datos antes de activar el acceso.');
        }

        $ultimaSesion = $sesionModel->getUltimaSesionAutorizada((int) $cliente['id']);
        $decision = $this->evaluateAccess($ultimaSesion);

        if (! $decision['allowed']) {
            $eventoModel->registrar((int) $cliente['id'], 'BLOQUEO_VENTANA_3_HORAS', 'Acceso rechazado por ventana de espera.', $context);

            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'identify')
                ->with('status', 'Ya usaste tu acceso gratuito recientemente.')
                ->with('waitUntil', $decision['wait_until'])
                ->with('old', $context);
        }

        $authorization = $this->authorizeHotspotAccess((int) $cliente['id'], $cliente['celular'], $context, 'Acceso autorizado para cliente existente en la sucursal ' . $context['branch_name'] . '.');

        if (! $authorization['success']) {
            $eventoModel->registrar((int) $cliente['id'], 'AUTORIZACION_FALLIDA', $authorization['message'], $context);

            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'identify')
                ->with('status', $authorization['message'])
                ->with('old', $context);
        }

        $eventoModel->registrar((int) $cliente['id'], 'AUTORIZACION_EXITOSA', 'Cliente existente autorizado.', $context);

        return redirect()->to('/hotspot/success')
            ->with('successData', [
                'nombre' => $cliente['nombre'],
                'expires_at' => $authorization['expires_at'],
                'branch_name' => $context['branch_name'],
                'router_name' => $context['router_name'],
            ]);
    }

    public function register()
    {
        $context = $this->resolveConnectionContext();

        if (! empty($context['context_error'])) {
            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'identify')
                ->with('status', $context['context_error']);
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[150]',
            'celular' => 'required|min_length[8]|max_length[30]',
            'correo' => 'required|valid_email|max_length[190]',
            'fecha_nacimiento' => 'required|valid_date[Y-m-d]',
            'acepta_terminos' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'register')
                ->with('errors', $this->validator->getErrors())
                ->with('old', array_merge($this->request->getPost(), $context))
                ->with('registrationContext', $context);
        }

        $clienteModel = new ClienteModel();
        $eventoModel = new EventoAccesoModel();

        $celular = $this->normalizePhone((string) $this->request->getPost('celular'));
        $correo = trim((string) $this->request->getPost('correo'));
        $fechaNacimiento = (string) $this->request->getPost('fecha_nacimiento');

        if (! $this->isBirthDateValid($fechaNacimiento)) {
            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'register')
                ->with('errors', ['fecha_nacimiento' => 'Ingresa una fecha de nacimiento valida.'])
                ->with('old', array_merge($this->request->getPost(), $context))
                ->with('registrationContext', $context);
        }

        $existing = $clienteModel->where('celular', $celular)->first();

        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
            'celular' => $celular,
            'correo' => $correo,
            'fecha_nacimiento' => $fechaNacimiento,
            'acepta_promociones' => $this->request->getPost('acepta_promociones') ? 1 : 0,
            'acepta_terminos' => 1,
            'fecha_actualizacion' => Time::now()->toDateTimeString(),
            'estado' => 'activo',
        ];

        if ($existing !== null) {
            $clienteModel->update((int) $existing['id'], $data);
            $clienteId = (int) $existing['id'];
            $eventoModel->registrar($clienteId, 'PERFIL_ACTUALIZADO', 'Cliente existente actualizo sus datos.', $context);
        } else {
            $data['fecha_registro'] = Time::now()->toDateTimeString();
            $clienteId = (int) $clienteModel->insert($data, true);
            $eventoModel->registrar($clienteId, 'REGISTRO_EXITOSO', 'Nuevo cliente registrado desde portal cautivo.', $context);
        }

        $authorization = $this->authorizeHotspotAccess($clienteId, $celular, $context, 'Acceso autorizado luego del registro en la sucursal ' . $context['branch_name'] . '.');

        if (! $authorization['success']) {
            $eventoModel->registrar($clienteId, 'AUTORIZACION_FALLIDA', $authorization['message'], $context);

            return redirect()->to($this->portalUrl($context))
                ->with('mode', 'register')
                ->with('status', $authorization['message'])
                ->with('old', array_merge($this->request->getPost(), $context))
                ->with('registrationContext', $context);
        }

        $eventoModel->registrar($clienteId, 'AUTORIZACION_EXITOSA', 'Cliente autorizado despues del registro.', $context);

        return redirect()->to('/hotspot/success')
            ->with('successData', [
                'nombre' => $data['nombre'],
                'expires_at' => $authorization['expires_at'],
                'branch_name' => $context['branch_name'],
                'router_name' => $context['router_name'],
            ]);
    }

    public function success(): string
    {
        $successData = session()->getFlashdata('successData');

        if ($successData === null) {
            return redirect()->to('/hotspot');
        }

        return view('portal/success', $successData);
    }

    private function evaluateAccess(?array $ultimaSesion): array
    {
        $now = Time::now();
        $expiresAt = Time::now()->addMinutes(self::DURACION_ACCESO_MINUTOS)->toDateTimeString();

        if ($ultimaSesion === null || empty($ultimaSesion['fecha_fin'])) {
            return [
                'allowed' => true,
                'expires_at' => $expiresAt,
                'wait_until' => null,
            ];
        }

        $allowedAt = Time::parse($ultimaSesion['fecha_fin'])->addHours(self::VENTANA_REINGRESO_HORAS);

        return [
            'allowed' => $now->getTimestamp() >= $allowedAt->getTimestamp(),
            'expires_at' => $expiresAt,
            'wait_until' => $allowedAt->toDateTimeString(),
        ];
    }

    private function createSessionRecord(int $clienteId, array $context, string $observaciones, bool $autorizado): array
    {
        $now = Time::now();
        $expiresAt = Time::now()->addMinutes(self::DURACION_ACCESO_MINUTOS);

        $sesionModel = new SesionHotspotModel();
        $sesionModel->insert([
            'cliente_id' => $clienteId,
            'sucursal_id' => $context['sucursal_id'],
            'router_id' => $context['router_id'],
            'branch_code' => $context['branch_code'],
            'router_code' => $context['router_code'],
            'mac_address' => $context['mac_address'],
            'ip_address' => $context['ip_address'],
            'hotspot_nombre' => $context['hotspot_nombre'],
            'fecha_inicio' => $now->toDateTimeString(),
            'fecha_fin' => $expiresAt->toDateTimeString(),
            'duracion_minutos' => self::DURACION_ACCESO_MINUTOS,
            'autorizado' => $autorizado ? 1 : 0,
            'observaciones' => $observaciones,
        ]);

        return [
            'expires_at' => $expiresAt->toDateTimeString(),
        ];
    }

    private function resolveConnectionContext(array $fallback = []): array
    {
        $routerCode = strtoupper(trim((string) ($this->request->getPost('router_code')
            ?: $this->request->getGet('router')
            ?: ($fallback['router_code'] ?? ''))));

        $context = [
            'branch_code' => strtoupper(trim((string) ($fallback['branch_code'] ?? ''))),
            'router_code' => $routerCode,
            'sucursal_id' => null,
            'router_id' => null,
            'branch_name' => null,
            'router_name' => null,
            'context_error' => null,
            'mac_address' => trim((string) $this->request->getPost('mac_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))
                ?: trim((string) $this->request->getGet('mac'))
                ?: trim((string) ($fallback['mac_address'] ?? '')),
            'ip_address' => trim((string) $this->request->getPost('ip_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))
                ?: trim((string) $this->request->getGet('ip'))
                ?: trim((string) $this->request->getGet('ip-address'))
                ?: trim((string) $this->request->getGet('ip_address'))
                ?: trim((string) ($fallback['ip_address'] ?? '')),
            'hotspot_nombre' => trim((string) $this->request->getPost('hotspot_nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS))
                ?: trim((string) $this->request->getGet('hotspot'))
                ?: trim((string) ($fallback['hotspot_nombre'] ?? '')),
            'link_login_only' => trim((string) ($this->request->getPost('link_login_only')
                ?: $this->request->getGet('link-login-only')
                ?: $this->request->getGet('link_login_only')
                ?: ($fallback['link_login_only'] ?? ''))),
            'link_orig' => trim((string) ($this->request->getPost('link_orig')
                ?: $this->request->getGet('link-orig')
                ?: $this->request->getGet('link_orig')
                ?: ($fallback['link_orig'] ?? ''))),
        ];

        if ($routerCode === '') {
            $context['context_error'] = 'No pudimos identificar el router de origen. Verifica que MikroTik envie el router_code en la redireccion.';

            return $context;
        }

        $routerModel = new MikrotikRouterModel();
        $sucursalModel = new SucursalModel();

        if ($routerCode !== '') {
            $router = $routerModel->findActiveByCode($routerCode);

            if ($router === null) {
                $context['context_error'] = 'El router recibido no esta registrado o esta inactivo.';

                return $context;
            }

            $sucursal = $sucursalModel->find($router['sucursal_id']);

            if ($sucursal === null || $sucursal['estado'] !== 'activa') {
                $context['context_error'] = 'La sucursal asociada al router no esta disponible.';

                return $context;
            }

            return array_merge($context, [
                'branch_code' => $sucursal['codigo'],
                'router_code' => $router['codigo'],
                'sucursal_id' => (int) $sucursal['id'],
                'router_id' => (int) $router['id'],
                'branch_name' => $sucursal['nombre'],
                'router_name' => $router['nombre_router'],
            ]);
        }

        return $context;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }

    private function profileNeedsCompletion(array $cliente): bool
    {
        return empty($cliente['nombre'])
            || empty($cliente['correo'])
            || empty($cliente['fecha_nacimiento'])
            || (int) ($cliente['acepta_terminos'] ?? 0) !== 1;
    }

    private function isBirthDateValid(string $date): bool
    {
        try {
            $birthDate = Time::createFromFormat('Y-m-d', $date);

            return $birthDate !== false && $birthDate->getTimestamp() <= Time::now()->getTimestamp();
        } catch (\Throwable) {
            return false;
        }
    }

    private function portalUrl(array $context): string
    {
        $query = array_filter([
            'router' => $context['router_code'] ?? null,
            'hotspot' => $context['hotspot_nombre'] ?? null,
            'mac' => $context['mac_address'] ?? null,
            'ip' => $context['ip_address'] ?? null,
            'link_login_only' => $context['link_login_only'] ?? null,
            'link_orig' => $context['link_orig'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '');

        $url = site_url('hotspot');

        return $query === [] ? $url : $url . '?' . http_build_query($query);
    }

    private function authorizeHotspotAccess(int $clienteId, string $celular, array $context, string $baseObservation): array
    {
        log_message('debug', 'Authorize hotspot access request cliente={cliente} router={router} mac={mac} ip={ip} hotspot={hotspot}', [
            'cliente' => $clienteId,
            'router' => $context['router_code'] ?? '',
            'mac' => $context['mac_address'] ?? '',
            'ip' => $context['ip_address'] ?? '',
            'hotspot' => $context['hotspot_nombre'] ?? '',
        ]);

        $routerModel = new MikrotikRouterModel();
        $router = $routerModel->find((int) $context['router_id']);

        if ($router === null) {
            $sessionData = $this->createSessionRecord(
                $clienteId,
                $context,
                $baseObservation . ' No se encontro la configuracion del router.',
                false
            );

            return [
                'success' => false,
                'message' => 'No pudimos cargar la configuracion del MikroTik de esta sucursal.',
                'expires_at' => $sessionData['expires_at'],
            ];
        }

        try {
            $service = new HotspotAuthorizationService();
            $mikrotikAccess = $service->authorize($router, $context, $clienteId, $celular);

            $sessionData = $this->createSessionRecord(
                $clienteId,
                $context,
                $baseObservation
                . ' Usuario hotspot listo: ' . $mikrotikAccess['hotspot_user'] . '.'
                . ' Login activo solicitado en el hotspot por ' . self::DURACION_ACCESO_MINUTOS . ' minutos.'
                . (! empty($mikrotikAccess['warning']) ? ' ' . $mikrotikAccess['warning'] : ''),
                true
            );

            if (! empty($mikrotikAccess['warning'])) {
                log_message('warning', 'MikroTik scheduler warning: {warning}', [
                    'warning' => $mikrotikAccess['warning'],
                ]);
            }

            return [
                'success' => true,
                'message' => 'Acceso autorizado correctamente.',
                'expires_at' => $sessionData['expires_at'],
            ];
        } catch (\Throwable $exception) {
            log_message('error', 'Authorize hotspot access failed cliente={cliente} router={router}: {error}', [
                'cliente' => $clienteId,
                'router' => $context['router_code'] ?? '',
                'error' => $exception->getMessage(),
            ]);

            $sessionData = $this->createSessionRecord(
                $clienteId,
                $context,
                $baseObservation . ' Error de autorizacion MikroTik: ' . $exception->getMessage(),
                false
            );

            return [
                'success' => false,
                'message' => 'No pudimos activar el acceso en MikroTik. Revisa conectividad, credenciales REST y configuracion del hotspot.',
                'expires_at' => $sessionData['expires_at'],
            ];
        }
    }
}
