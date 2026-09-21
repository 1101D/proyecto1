/// Configuración de la URL base de la API.
///
/// - Probando en un dispositivo Android conectado por USB:
///     adb reverse tcp:8001 tcp:8001
///   y dejá el valor por defecto (127.0.0.1).
/// - Probando en el emulador de Android:
///     usá "10.0.2.2" en vez de "127.0.0.1".
/// - Probando en un dispositivo por Wi-Fi (misma red que la Mac):
///     usá la IP local de la Mac, ej. "192.168.1.10".
class ApiConfig {
  static const String baseUrl = 'http://127.0.0.1:8001/api';
}
