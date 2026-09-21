import 'package:flutter/foundation.dart';
import '../models/user.dart';
import 'api_client.dart';

class AuthService extends ChangeNotifier {
  final ApiClient _client;

  AuthService(this._client);

  AppUser? currentUser;
  bool isLoading = true;

  Future<void> bootstrap() async {
    if (await _client.hasToken) {
      try {
        final json = await _client.get('/me');
        currentUser = AppUser.fromJson(json as Map<String, dynamic>);
      } catch (_) {
        await _client.clearToken();
      }
    }
    isLoading = false;
    notifyListeners();
  }

  Future<void> login(String email, String password) async {
    final json = await _client.post('/login', body: {
      'email': email,
      'password': password,
    }, auth: false);

    await _client.saveToken(json['token'] as String);
    currentUser = AppUser.fromJson(json['user'] as Map<String, dynamic>);
    notifyListeners();
  }

  Future<void> logout() async {
    try {
      await _client.post('/logout');
    } catch (_) {}
    await _client.clearToken();
    currentUser = null;
    notifyListeners();
  }

  bool get isAuthenticated => currentUser != null;
}
