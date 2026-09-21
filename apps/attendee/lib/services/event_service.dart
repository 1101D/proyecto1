import '../models/event.dart';
import 'api_client.dart';

class EventService {
  final ApiClient _client;

  EventService(this._client);

  Future<List<Event>> list({String? query, String? category}) async {
    final json = await _client.get('/events', query: {
      if (query != null && query.isNotEmpty) 'q': query,
      if (category != null && category.isNotEmpty) 'category': category,
    }, auth: false);

    final data = json['data'] as List<dynamic>;
    return data.map((e) => Event.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<List<Event>> upcoming() async {
    final json = await _client.get('/events/upcoming', auth: false);
    final data = json['data'] as List<dynamic>;
    return data.map((e) => Event.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Map<String, List<String>>> categories() async {
    final json = await _client.get('/events/categories', auth: false) as Map<String, dynamic>;
    return json.map((key, value) => MapEntry(key, (value as List<dynamic>).cast<String>()));
  }

  Future<Event> show(int id) async {
    final json = await _client.get('/events/$id', auth: false);
    return Event.fromJson(json as Map<String, dynamic>);
  }

  Future<void> like(int eventId) => _client.post('/events/$eventId/like');

  Future<void> unlike(int eventId) => _client.delete('/events/$eventId/like');

  Future<List<Event>> saved() async {
    final json = await _client.get('/me/saved-events') as List<dynamic>;
    return json.map((e) => Event.fromJson(e as Map<String, dynamic>)).toList();
  }
}
