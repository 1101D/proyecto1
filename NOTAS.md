# Notas del proyecto — Eventz (proyecto1)

Bitácora de la sesión de trabajo con Claude Code, para retomar el proyecto más adelante.

## Estado actual (2026-09-21)

- **`web/`** — App Laravel funcional. Panel de organizador, reserva de entradas, administración. Corriendo local con `php artisan serve --port=8001` (SQLite en `web/database/database.sqlite`).
- **`api/`** — API REST recién construida con **Laravel Sanctum** (auth por token). 26 endpoints en `web/routes/api.php`, controladores en `web/app/Http/Controllers/Api/`. Probada de punta a punta: registro, login, listar/buscar eventos, reservar entradas, dashboard del organizador, escaneo de QR (incluye rechazo de entradas duplicadas).
- **`apps/`** — Todavía sin código. Solo están los requisitos definidos (ver abajo). Pendiente decidir stack (nativo vs. Flutter/React Native) y diseño.

## Requisitos pedidos para las apps móviles

### 1. App de organizadores (iOS y Android)
- Panel de control atractivo
- Inicio de sesión del organizador
- Escáner de código QR
- Lista de asistentes al evento
- Seguimiento de ventas brutas
- Lista de pedidos

### 2. App de usuarios/asistentes (iOS y Android)
- Registrarse
- Iniciar sesión
- Página de inicio
- Dar "Me gusta" / marcar evento
- Compartir evento en redes sociales
- Buscar evento por categoría
- Buscar evento por nombre del evento
- Reservar entradas para el evento
- Obtener una entrada en la aplicación
- Ver próximos eventos
- Ver eventos guardados
- Configuración del perfil

Cada uno de estos puntos ya tiene su endpoint correspondiente en la API (ver `api/README.md` para el detalle completo).

## Cómo retomar el proyecto

```bash
cd /Users/dangrares/Desktop/proyecto1/web
composer install
npm install
cp .env.example .env        # si no existe ya
php artisan key:generate
php artisan migrate
npm run dev                 # compila los assets
php artisan serve --port=8001
```

La API queda disponible en `http://localhost:8001/api/...`. Ver `api/README.md` para la lista de endpoints.

## Próximo paso sugerido

Definir stack técnico para `apps/` (nativo Swift/Kotlin vs. multiplataforma) y empezar por la app de usuarios o la de organizadores, consumiendo la API ya lista.

## Otros proyectos relacionados (misma cuenta de GitHub)

- **`proyecto0`** (https://github.com/1101D/proyecto0) — Portafolio personal en PHP puro, con galería de proyectos Laravel. Vive en `/Users/dangrares/Desktop/portafolio-php`. No tiene relación de código con `proyecto1`, son proyectos separados.
