# API

Acá va a vivir la API REST que van a consumir las apps de Android e iOS (`../apps`).

Pendiente: autenticación por token (Laravel Sanctum), endpoints de eventos, entradas, reservas y perfil.

## Endpoints necesarios (borrador)

Derivados de los requisitos de `../apps/README.md`. Se apoyan en los modelos que ya existen en `web/app/Models`: `Event`, `Order`, `Organization`, `Ticket`, `TicketType`, `User`.

### Auth (ambas apps)
- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `PATCH /api/me` — configuración del perfil

### Para la app de usuarios/asistentes
- `GET /api/events` — listado + filtros por categoría y por nombre (`?category=`, `?q=`)
- `GET /api/events/{event}` — detalle del evento
- `GET /api/events/upcoming` — próximos eventos
- `POST /api/events/{event}/like` / `DELETE /api/events/{event}/like` — marcar/desmarcar favorito
- `GET /api/me/saved-events` — eventos guardados
- `POST /api/events/{event}/orders` — reservar entradas (crea `Order` + `Ticket`s)
- `GET /api/me/orders` — mis pedidos
- `GET /api/tickets/{ticket}` — entrada digital (con datos para generar el QR)

### Para la app de organizadores
- `GET /api/organizer/dashboard` — resumen (ventas brutas, asistentes, próximos eventos)
- `GET /api/organizer/events/{event}/attendees` — lista de asistentes
- `GET /api/organizer/events/{event}/orders` — lista de pedidos
- `GET /api/organizer/events/{event}/sales` — seguimiento de ventas brutas
- `POST /api/organizer/tickets/scan` — validar entrada escaneada por QR (recibe el código, marca el `Ticket` como usado)

## Pendiente de definir
- Autenticación: Sanctum con tokens personales (uno por dispositivo)
- Formato de la respuesta de error/paginación estándar
- Reglas de autorización: separar rutas de organizador (dueño de la `Organization`) vs. usuario normal
