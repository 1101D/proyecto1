import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class ApiException implements Exception {
  final int statusCode;
  final String message;

  ApiException(this.statusCode, this.message);

  @override
  String toString() => message;
}

class ApiClient {
  static const _tokenKey = 'auth_token';

  Future<String?> get _token async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }

  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
  }

  Future<bool> get hasToken async => (await _token) != null;

  Future<Map<String, String>> _headers({bool auth = true}) async {
    final headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };

    if (auth) {
      final token = await _token;
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }

  Uri _uri(String path, [Map<String, dynamic>? query]) {
    final cleanedQuery = query?.map((key, value) => MapEntry(key, value.toString()));
    return Uri.parse('${ApiConfig.baseUrl}$path').replace(queryParameters: cleanedQuery);
  }

  dynamic _decode(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (response.body.isEmpty) return null;
      return jsonDecode(response.body);
    }

    String message = 'Ocurrió un error (${response.statusCode}).';
    try {
      final body = jsonDecode(response.body);
      if (body is Map && body['message'] != null) {
        message = body['message'].toString();
      }
    } catch (_) {}

    throw ApiException(response.statusCode, message);
  }

  Future<dynamic> get(String path, {Map<String, dynamic>? query, bool auth = true}) async {
    final response = await http.get(_uri(path, query), headers: await _headers(auth: auth));
    return _decode(response);
  }

  Future<dynamic> post(String path, {Map<String, dynamic>? body, bool auth = true}) async {
    final response = await http.post(
      _uri(path),
      headers: await _headers(auth: auth),
      body: body != null ? jsonEncode(body) : null,
    );
    return _decode(response);
  }

  Future<dynamic> patch(String path, {Map<String, dynamic>? body}) async {
    final response = await http.patch(
      _uri(path),
      headers: await _headers(),
      body: body != null ? jsonEncode(body) : null,
    );
    return _decode(response);
  }

  Future<dynamic> delete(String path) async {
    final response = await http.delete(_uri(path), headers: await _headers());
    return _decode(response);
  }
}
