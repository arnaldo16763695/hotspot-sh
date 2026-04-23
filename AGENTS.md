# AGENTS.md

## Nombre del proyecto
Hotspot WiFi con portal cautivo, registro de clientes y base para campañas futuras

## Objetivo general
Implementar un sistema de acceso WiFi gratuito mediante Hotspot MikroTik, donde el usuario deba identificarse o registrarse antes de navegar. El sistema debe almacenar sus datos para futuras campañas de marketing por correo y SMS, así como automatizaciones de cumpleaños.

## Resultado esperado del MVP
El cliente se conecta al WiFi, es redirigido al portal cautivo, ingresa su celular y el sistema decide una de estas dos rutas:

1. Si el cliente ya existe y ya pasaron 3 horas desde su última sesión autorizada, recibe Internet por 1 hora.
2. Si el cliente no existe, completa el registro y luego recibe Internet por 1 hora.
3. Si el cliente ya existe pero aún no han pasado 3 horas, el sistema muestra un mensaje informativo y no concede acceso.

## Objetivos funcionales
- Obligar al usuario a pasar por un portal cautivo antes de tener Internet.
- Permitir acceso gratuito por 1 hora.
- Permitir una nueva sesión solo cada 3 horas.
- Registrar datos del cliente:
  - nombre
  - celular
  - correo
  - fecha de nacimiento
- Guardar trazabilidad básica:
  - fecha de registro
  - fecha de acceso
  - IP
  - MAC address
  - hotspot o sucursal
- Preparar la base de datos para:
  - campañas por correo
  - campañas por SMS
  - mensajes automáticos de cumpleaños

## Alcance del MVP
El MVP debe resolver únicamente el flujo de captura, validación y autorización de Internet. Todo lo relacionado con campañas, panel administrativo avanzado, segmentación y automatizaciones queda preparado a nivel de datos, pero no se implementa todavía.

## Principios del proyecto
- Sin identificación válida, no hay Internet.
- El dato principal de identificación es el celular.
- El correo es obligatorio para usuarios nuevos.
- No se debe duplicar un cliente por celular.
- El frontend debe ser simple, rápido y usable en móviles.
- Todos los textos visibles al usuario deben escribirse en español correcto, con tildes, signos y ortografía adecuados.
- La lógica de negocio debe vivir en backend, no en el frontend.
- La integración con MikroTik debe quedar desacoplada para poder cambiar el mecanismo más adelante si hace falta.

## Flujo funcional del usuario

### Paso 1: conexión al WiFi
El cliente se conecta a la red inalámbrica del hotspot.

### Paso 2: interceptación del Hotspot
MikroTik intercepta cualquier intento de navegación y redirige al portal cautivo.

### Paso 3: acceso controlado al portal
El cliente aún no tiene Internet abierta. Solo puede acceder al portal y a sus recursos mediante la configuración de walled garden.

### Paso 4: identificación inicial
La primera pantalla solicita únicamente:
- número de celular

### Paso 5: validación del celular
El backend consulta si el celular ya existe.

#### Si el celular existe
- buscar la última sesión autorizada
- calcular si ya pasaron 3 horas desde `fecha_fin` o desde la última autorización efectiva
- si ya pasaron 3 horas, permitir nueva sesión de 1 hora
- si no han pasado 3 horas, mostrar mensaje de espera y no autorizar Internet

#### Si el celular no existe
Mostrar un formulario completo con:
- nombre completo
- celular
- correo electrónico
- fecha de nacimiento
- aceptación de términos y política de privacidad
- aceptación de comunicaciones promocionales

### Paso 6: registro
Guardar los datos del cliente y registrar el evento de alta.

### Paso 7: autorización
Si la validación o el registro fueron correctos, el backend envía la orden a MikroTik para autorizar acceso durante 1 hora.

### Paso 8: cierre y trazabilidad
Registrar la sesión generada y los datos técnicos disponibles:
- IP
- MAC
- hotspot
- hora de inicio
- hora prevista de fin
- estado de autorización

## Reglas de negocio
- Sin registro o identificación válida, no hay Internet.
- El acceso es gratuito.
- La duración de cada autorización es de 60 minutos.
- La ventana mínima para una nueva autorización es de 3 horas.
- El celular debe ser único.
- El correo es obligatorio para usuarios nuevos.
- La aceptación de términos es obligatoria para autorizar acceso.
- La aceptación de promociones no es obligatoria para el acceso, pero sí debe registrarse.
- Si la autorización en MikroTik falla, debe registrarse el intento y no mostrarse como acceso exitoso.
- Si el cliente existe pero le faltan datos requeridos del perfil, el sistema puede solicitar completar datos antes de autorizar.

## Arquitectura propuesta

### 1. MikroTik
Responsabilidades:
- manejar el Hotspot
- interceptar la navegación
- redirigir al portal cautivo
- permitir acceso al dominio del portal mediante walled garden
- conceder acceso temporal tras la autorización del backend

### 2. Portal web
Responsabilidades:
- mostrar formularios
- validar datos de entrada
- consultar clientes por celular
- registrar nuevos clientes
- decidir si se autoriza o no el acceso
- comunicarse con MikroTik
- mostrar mensajes claros al usuario

### 3. Backend
Responsabilidades:
- implementar reglas de negocio
- consultar y persistir información
- registrar eventos
- gestionar integración con MikroTik
- exponer endpoints del portal

### 4. Base de datos
Responsabilidades:
- almacenar clientes
- almacenar sesiones
- almacenar eventos de acceso
- servir de base para campañas futuras

## Decisión técnica recomendada para el MVP
Como el workspace aún no contiene aplicación base, el enfoque más simple y mantenible para arrancar es:

- Frontend: HTML5 + Bootstrap 5
- Backend: PHP 8.x
- Base de datos: MySQL o MariaDB

Motivos:
- encaja bien con un entorno XAMPP
- reduce tiempo de arranque
- facilita despliegue en hosting o VPS simple
- es suficiente para el flujo MVP

Si más adelante se desea panel administrativo grande o automatizaciones más complejas, el backend puede migrarse o crecer sin romper el modelo de datos.

## Estructura del sistema recomendada

### Portal público
- pantalla inicial de celular
- pantalla de registro
- pantalla de acceso concedido
- pantalla de espera por ventana de 3 horas
- pantalla de error técnico

### API interna del portal
- validación de celular
- registro de cliente
- autorización de acceso
- registro de eventos

### Módulo de integración MikroTik
- encapsular login o autorización
- registrar respuestas del equipo
- manejar errores de conectividad

## Endpoints sugeridos para el MVP

### `POST /api/hotspot/identify`
Entrada:
- celular
- mac_address
- ip_address
- hotspot_nombre

Salida posible:
- `EXISTE_Y_AUTORIZABLE`
- `EXISTE_EN_ESPERA`
- `NO_EXISTE`
- `DATOS_INCOMPLETOS`

### `POST /api/hotspot/register`
Entrada:
- nombre
- celular
- correo
- fecha_nacimiento
- acepta_terminos
- acepta_promociones
- mac_address
- ip_address
- hotspot_nombre

Salida posible:
- `REGISTRO_OK_Y_AUTORIZADO`
- `REGISTRO_OK_PERO_ERROR_AUTORIZACION`
- `VALIDACION_ERROR`

### `POST /api/hotspot/authorize`
Uso interno o controlado por backend.

Entrada:
- cliente_id
- mac_address
- ip_address
- hotspot_nombre

Salida:
- autorización exitosa o fallida

### `POST /api/hotspot/event`
Opcional para auditoría o depuración.

## Modelo de datos inicial

### Tabla `clientes`
- `id`
- `nombre`
- `celular`
- `correo`
- `fecha_nacimiento`
- `acepta_promociones`
- `acepta_terminos`
- `fecha_registro`
- `fecha_actualizacion`
- `estado`

Reglas:
- índice único por `celular`
- índice por `correo` si se quiere prevenir duplicados suaves
- `estado` puede manejar valores como `activo`, `bloqueado`, `pendiente`

### Tabla `sesiones_hotspot`
- `id`
- `cliente_id`
- `mac_address`
- `ip_address`
- `hotspot_nombre`
- `fecha_inicio`
- `fecha_fin`
- `duracion_minutos`
- `autorizado`
- `observaciones`

Reglas:
- `autorizado` indica si la sesión realmente fue concedida
- `fecha_fin` puede guardarse como hora prevista de expiración o actualizarse con cierre real cuando sea posible

### Tabla `eventos_acceso`
- `id`
- `cliente_id`
- `tipo_evento`
- `descripcion`
- `fecha_evento`

Ejemplos de `tipo_evento`:
- `INTENTO_IDENTIFICACION`
- `USUARIO_NO_EXISTE`
- `USUARIO_ENCONTRADO`
- `BLOQUEO_VENTANA_3_HORAS`
- `REGISTRO_EXITOSO`
- `AUTORIZACION_EXITOSA`
- `AUTORIZACION_FALLIDA`

## Consideraciones de validación

### Celular
- normalizar formato antes de buscar
- guardar solo números o un formato estándar definido
- aplicar longitud mínima y máxima según el país objetivo

### Correo
- validar formato
- marcarlo como obligatorio para cliente nuevo

### Fecha de nacimiento
- validar que sea una fecha real
- evitar fechas futuras

### Consentimientos
- `acepta_terminos` debe ser obligatorio
- `acepta_promociones` es opcional, pero debe guardarse con fecha o contexto si luego se quiere trazabilidad legal más fuerte

## Lógica de autorización
Regla recomendada para el cálculo:

1. Buscar la última sesión autorizada del cliente.
2. Si no existe, se puede autorizar.
3. Si existe, calcular `proximo_acceso_permitido = fecha_fin + 3 horas`.
4. Si la fecha actual es mayor o igual a `proximo_acceso_permitido`, autorizar.
5. Si no, rechazar temporalmente y mostrar hora aproximada de reintento.

Nota:
Para evitar inconsistencias, es preferible usar `fecha_fin` de la última sesión autorizada y no solo `fecha_inicio`.

## Integración con MikroTik
La implementación concreta dependerá del método elegido, pero el sistema debe contemplar una interfaz como esta:

- `authorizeClient(mac, ip, duration, hotspot)`
- `disconnectClient(mac)`
- `testConnection()`

Opciones comunes:
- API de MikroTik
- RouterOS API
- integración por usuario temporal del hotspot
- RADIUS en una fase futura

Para el MVP, la mejor estrategia es usar una integración directa y simple, siempre encapsulada en un servicio independiente del resto de la lógica.

## Walled garden
Antes de la autorización, MikroTik debe permitir:
- dominio del portal
- subdominios del portal si aplican
- archivos CSS, JS, imágenes y fuentes del portal
- endpoints de envío del formulario

No debe permitirse navegación abierta fuera de esos recursos.

## Mensajes UX mínimos

### Usuario existente con acceso permitido
"Tu acceso ha sido activado por 1 hora."

### Usuario existente dentro de la ventana de espera
"Ya usaste tu acceso gratuito recientemente. Podrás volver a conectarte después de las 3 horas requeridas."

### Usuario nuevo
"Completa tus datos para activar tu acceso gratuito."

### Error técnico
"No pudimos activar tu acceso en este momento. Por favor, intenta nuevamente en unos minutos."

## Fases de implementación

### Fase 1: diseño funcional
- cerrar flujo del usuario
- definir reglas de negocio
- definir estructura de datos
- definir contrato con MikroTik

### Fase 2: infraestructura MikroTik
- activar Hotspot
- configurar portal cautivo
- configurar walled garden
- probar redirección

### Fase 3: backend y base de datos
- crear tablas
- crear endpoints
- implementar búsqueda por celular
- implementar registro
- implementar control de ventana de 3 horas

### Fase 4: integración con MikroTik
- autorizar acceso por 1 hora
- manejar errores de autorización
- registrar sesiones e intentos

### Fase 5: UI del portal
- pantalla inicial de celular
- formulario de registro
- mensajes de autorización y espera
- diseño responsive

### Fase 6: pruebas
- usuario nuevo
- usuario ya registrado con acceso permitido
- usuario bloqueado por ventana de 3 horas
- usuario con datos incompletos
- error de DB
- error de MikroTik

### Fase 7: evolución futura
- panel administrativo
- filtros de clientes
- exportación
- campañas por email
- campañas por SMS
- automatizaciones de cumpleaños

## Casos de prueba mínimos

### Caso 1: usuario nuevo
Resultado esperado:
- se muestra formulario completo
- se registra cliente
- se registra sesión
- se autoriza Internet

### Caso 2: usuario existente fuera de la ventana de espera
Resultado esperado:
- no se solicita registro completo
- se registra nueva sesión
- se autoriza Internet

### Caso 3: usuario existente dentro de la ventana de espera
Resultado esperado:
- no se autoriza Internet
- se muestra mensaje de espera
- se registra evento

### Caso 4: formulario inválido
Resultado esperado:
- no se crea cliente
- no se autoriza Internet
- se muestran errores de validación

### Caso 5: falla de integración con MikroTik
Resultado esperado:
- el cliente puede quedar registrado si el alta fue correcta
- la sesión debe marcarse como no autorizada
- debe registrarse evento de error

## Riesgos técnicos a vigilar
- diferencias de hora entre servidor web y MikroTik
- formato inconsistente del número de celular
- recursos no incluidos correctamente en walled garden
- sesiones duplicadas por doble envío del formulario
- autorización en MikroTik exitosa pero fallo al guardar en DB
- fallo en DB luego de validar al usuario

## Recomendaciones de implementación
- usar transacciones de base de datos para registro y sesión cuando aplique
- añadir protección contra doble clic o reenvío del formulario
- registrar logs técnicos separados de los mensajes mostrados al usuario
- centralizar validaciones de negocio en backend
- dejar preparada una columna o estructura para trazabilidad de consentimiento si luego se requiere soporte legal más fuerte

## Entregables esperados
- flujo funcional documentado
- esquema de base de datos
- estructura de endpoints o rutas
- interfaz del portal cautivo
- lógica de validación de acceso
- integración con MikroTik
- documentación de instalación y pruebas

## Prioridades del agente
Trabajar en este orden:

1. claridad del flujo funcional
2. estabilidad del registro de datos
3. integración con MikroTik
4. control del tiempo de acceso
5. experiencia simple del usuario
6. preparación para campañas futuras

## Siguiente paso recomendado
Convertir este documento en una primera estructura de aplicación con:

1. esquema SQL inicial
2. endpoints del flujo hotspot
3. pantallas del portal cautivo
4. adaptador de integración MikroTik

Ese es el punto de partida correcto para construir el MVP sin sobrecargarlo con funcionalidades futuras.
