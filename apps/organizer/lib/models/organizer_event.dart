class OrganizerEvent {
  final int id;
  final String title;
  final DateTime startAt;
  final int ticketTypesCount;
  final int ticketsSoldCount;

  OrganizerEvent({
    required this.id,
    required this.title,
    required this.startAt,
    required this.ticketTypesCount,
    required this.ticketsSoldCount,
  });

  factory OrganizerEvent.fromJson(Map<String, dynamic> json) => OrganizerEvent(
        id: json['id'] as int,
        title: json['title'] as String,
        startAt: DateTime.parse(json['start_at'] as String),
        ticketTypesCount: json['ticket_types_count'] as int? ?? 0,
        ticketsSoldCount: json['tickets_sold_count'] as int? ?? 0,
      );
}

class DashboardSummary {
  final int totalEvents;
  final int upcomingEvents;
  final double totalRevenue;
  final int totalTicketsSold;
  final List<OrganizerEvent> events;

  DashboardSummary({
    required this.totalEvents,
    required this.upcomingEvents,
    required this.totalRevenue,
    required this.totalTicketsSold,
    required this.events,
  });

  factory DashboardSummary.fromJson(Map<String, dynamic> json) => DashboardSummary(
        totalEvents: json['total_events'] as int,
        upcomingEvents: json['upcoming_events'] as int,
        totalRevenue: double.parse(json['total_revenue'].toString()),
        totalTicketsSold: json['total_tickets_sold'] as int,
        events: (json['events'] as List<dynamic>)
            .map((e) => OrganizerEvent.fromJson(e as Map<String, dynamic>))
            .toList(),
      );
}
