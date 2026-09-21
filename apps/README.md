# Apps móviles

Apps en **Flutter** (un solo código para Android e iOS) que consumen la API (`../api`):

- `organizer/` — App de organizadores. ✅ Implementada (Android probado con APK real).
- `attendee/` — App de usuarios/asistentes. ✅ Implementada (Android probado con APK real).

iOS todavía no se probó en dispositivo (falta configurar CocoaPods y firma), pero el mismo código de cada proyecto compila para ambas plataformas.

## Cómo correrlas

Con la API corriendo (`cd ../api/../web && php artisan serve --port=8001`):

```bash
# Requiere Flutter, JDK 17 y Android SDK instalados (ver NOTAS.md en la raíz del repo)
cd organizer   # o attendee
flutter pub get
flutter run    # con un emulador o dispositivo conectado
```

Para probar en un Android físico por USB:
```bash
adb reverse tcp:8001 tcp:8001   # así la app llega a la API que corre en la Mac
flutter install
```

La URL de la API se configura en `lib/config/api_config.dart` de cada app.

## 1. App de organizadores (iOS y Android)

Para quienes crean y administran eventos.

- [x] Panel de control atractivo (resumen del/los evento/s)
- [x] Inicio de sesión del organizador
- [x] Escáner de código QR (para validar entradas en la puerta)
- [x] Lista de asistentes al evento
- [x] Seguimiento de ventas brutas
- [x] Lista de pedidos

## 2. App de usuarios/asistentes (iOS y Android)

Para el público que descubre y reserva entradas.

- [x] Registrarse
- [x] Iniciar sesión
- [x] Página de inicio
- [x] Dar "Me gusta" / marcar evento (favoritos)
- [x] Compartir evento en redes sociales
- [x] Buscar evento por categoría
- [x] Buscar evento por nombre del evento
- [x] Reservar entradas para el evento
- [x] Obtener una entrada en la aplicación (entrada digital, con QR)
- [x] Ver próximos eventos (dentro de "Explorar")
- [x] Ver eventos guardados
- [x] Configuración del perfil

## Pendiente

- Probar y firmar la build de iOS (necesita CocoaPods + cuenta de Apple Developer para dispositivo físico)
- Reemplazar los widget tests placeholder por una cobertura más completa
- Íconos/branding propios de la app (hoy usan el ícono por defecto de Flutter)
- Registro específico para organizadores (hoy se crean asociando una `Organization` a un usuario desde el panel web)
