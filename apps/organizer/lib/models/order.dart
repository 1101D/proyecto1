class OrganizerOrder {
  final int id;
  final double total;
  final String status;
  final String userName;
  final String userEmail;
  final int ticketCount;
  final DateTime createdAt;

  OrganizerOrder({
    required this.id,
    required this.total,
    required this.status,
    required this.userName,
    required this.userEmail,
    required this.ticketCount,
    required this.createdAt,
  });

  factory OrganizerOrder.fromJson(Map<String, dynamic> json) => OrganizerOrder(
        id: json['id'] as int,
        total: double.parse(json['total'].toString()),
        status: json['status'] as String,
        userName: json['user']?['name'] as String? ?? '—',
        userEmail: json['user']?['email'] as String? ?? '',
        ticketCount: (json['tickets'] as List<dynamic>? ?? []).length,
        createdAt: DateTime.parse(json['created_at'] as String),
      );
}
