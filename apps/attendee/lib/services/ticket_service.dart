import '../models/ticket.dart';
import 'api_client.dart';

class TicketService {
  final ApiClient _client;

  TicketService(this._client);

  Future<void> reserve(int eventId, Map<int, int> quantities) async {
    await _client.post('/events/$eventId/orders', body: {
      'quantities': quantities.map((key, value) => MapEntry(key.toString(), value)),
    });
  }

  Future<List<Ticket>> myTickets() async {
    final json = await _client.get('/me/tickets') as List<dynamic>;
    return json.map((e) => Ticket.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Ticket> cancel(int ticketId) async {
    final json = await _client.post('/tickets/$ticketId/cancel');
    return Ticket.fromJson(json as Map<String, dynamic>);
  }
}
