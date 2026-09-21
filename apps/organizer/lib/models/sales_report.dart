class TicketTypeSales {
  final String ticketType;
  final int sold;
  final int available;
  final double revenue;

  TicketTypeSales({
    required this.ticketType,
    required this.sold,
    required this.available,
    required this.revenue,
  });

  factory TicketTypeSales.fromJson(Map<String, dynamic> json) => TicketTypeSales(
        ticketType: json['ticket_type'] as String,
        sold: json['sold'] as int,
        available: json['available'] as int,
        revenue: double.parse(json['revenue'].toString()),
      );
}

class SalesReport {
  final int eventId;
  final double grossRevenue;
  final List<TicketTypeSales> byTicketType;

  SalesReport({required this.eventId, required this.grossRevenue, required this.byTicketType});

  factory SalesReport.fromJson(Map<String, dynamic> json) => SalesReport(
        eventId: json['event_id'] as int,
        grossRevenue: double.parse(json['gross_revenue'].toString()),
        byTicketType: (json['by_ticket_type'] as List<dynamic>)
            .map((e) => TicketTypeSales.fromJson(e as Map<String, dynamic>))
            .toList(),
      );
}
