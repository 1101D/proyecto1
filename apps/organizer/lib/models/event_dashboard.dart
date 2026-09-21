class TicketTypeBreakdown {
  final String name;
  final double price;
  final int sold;
  final int quantity;
  final double revenue;

  TicketTypeBreakdown({
    required this.name,
    required this.price,
    required this.sold,
    required this.quantity,
    required this.revenue,
  });

  factory TicketTypeBreakdown.fromJson(Map<String, dynamic> json) => TicketTypeBreakdown(
        name: json['name'] as String,
        price: double.parse(json['price'].toString()),
        sold: json['sold'] as int,
        quantity: json['quantity'] as int,
        revenue: double.parse(json['revenue'].toString()),
      );
}

class EventDashboard {
  final double revenue;
  final int ticketsSold;
  final int ticketsCancelled;
  final List<TicketTypeBreakdown> salesByType;

  EventDashboard({
    required this.revenue,
    required this.ticketsSold,
    required this.ticketsCancelled,
    required this.salesByType,
  });

  factory EventDashboard.fromJson(Map<String, dynamic> json) => EventDashboard(
        revenue: double.parse(json['revenue'].toString()),
        ticketsSold: json['tickets_sold'] as int,
        ticketsCancelled: json['tickets_cancelled'] as int,
        salesByType: (json['sales_by_type'] as List<dynamic>)
            .map((e) => TicketTypeBreakdown.fromJson(e as Map<String, dynamic>))
            .toList(),
      );
}
