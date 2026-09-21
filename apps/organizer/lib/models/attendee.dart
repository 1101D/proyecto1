class Attendee {
  final int id;
  final String userName;
  final String userEmail;
  final String ticketTypeName;
  final String code;
  final String status;

  Attendee({
    required this.id,
    required this.userName,
    required this.userEmail,
    required this.ticketTypeName,
    required this.code,
    required this.status,
  });

  factory Attendee.fromJson(Map<String, dynamic> json) => Attendee(
        id: json['id'] as int,
        userName: json['user']?['name'] as String? ?? '—',
        userEmail: json['user']?['email'] as String? ?? '',
        ticketTypeName: json['ticket_type']?['name'] as String? ?? '—',
        code: json['code'] as String,
        status: json['status'] as String,
      );
}
