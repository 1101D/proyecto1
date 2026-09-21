import '../models/attendee.dart';
import '../models/event_dashboard.dart';
import '../models/order.dart';
import '../models/organizer_event.dart';
import '../models/sales_report.dart';
import 'api_client.dart';

class ScanResult {
  final bool success;
  final String message;

  ScanResult({required this.success, required this.message});
}

class OrganizerService {
  final ApiClient _client;

  OrganizerService(this._client);

  Future<DashboardSummary> dashboard() async {
    final json = await _client.get('/organizer/dashboard');
    return DashboardSummary.fromJson(json as Map<String, dynamic>);
  }

  Future<EventDashboard> eventDashboard(int eventId) async {
    final json = await _client.get('/organizer/events/$eventId/dashboard');
    return EventDashboard.fromJson(json as Map<String, dynamic>);
  }

  Future<List<Attendee>> attendees(int eventId) async {
    final json = await _client.get('/organizer/events/$eventId/attendees') as List<dynamic>;
    return json.map((e) => Attendee.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<List<OrganizerOrder>> orders(int eventId) async {
    final json = await _client.get('/organizer/events/$eventId/orders') as List<dynamic>;
    return json.map((e) => OrganizerOrder.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<SalesReport> sales(int eventId) async {
    final json = await _client.get('/organizer/events/$eventId/sales');
    return SalesReport.fromJson(json as Map<String, dynamic>);
  }

  Future<ScanResult> scan(String code) async {
    try {
      final json = await _client.post('/organizer/tickets/scan', body: {'code': code});
      return ScanResult(success: true, message: (json as Map)['message'] as String);
    } on ApiException catch (e) {
      return ScanResult(success: false, message: e.message);
    }
  }
}
