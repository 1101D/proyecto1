# Proyecto 1 — Eventz

Plataforma de gestión de eventos. Este repositorio está organizado en 3 partes:

- **[`web/`](./web)** — Aplicación web (Laravel + PHP). Panel de organizador, reserva de entradas, administración. Ya funcional.
- **[`api/`](./api)** — API REST para las apps móviles. Todavía no implementada.
- **[`apps/`](./apps)** — Apps de Android (APK) e iOS. Todavía no implementadas.

## Cómo correr la web localmente

```bash
cd web
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
