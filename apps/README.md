# Apps móviles

Acá van a vivir las apps nativas/multiplataforma que consuman la API (`../api`):

- `android/` — App de Android (APK).
- `ios/` — App de iOS.

Todavía no implementadas. A continuación, los requisitos funcionales de cada una.

## 1. App de organizadores (iOS y Android)

Para quienes crean y administran eventos.

- Panel de control atractivo (resumen del/los evento/s)
- Inicio de sesión del organizador
- Escáner de código QR (para validar entradas en la puerta)
- Lista de asistentes al evento
- Seguimiento de ventas brutas
- Lista de pedidos

## 2. App de usuarios/asistentes (iOS y Android)

Para el público que descubre y reserva entradas.

- Registrarse
- Iniciar sesión
- Página de inicio
- Dar "Me gusta" / marcar evento (favoritos)
- Compartir evento en redes sociales
- Buscar evento por categoría
- Buscar evento por nombre del evento
- Reservar entradas para el evento
- Obtener una entrada en la aplicación (entrada digital, ej. con QR)
- Ver próximos eventos
- Ver eventos guardados
- Configuración del perfil

## Pendiente de definir

- Stack técnico: nativo (Swift/Kotlin) vs. multiplataforma (Flutter, React Native, etc.)
- Diseño/UI (wireframes o mockups)
- Endpoints concretos de la API que cada pantalla va a consumir (ver `../api/README.md`)
