import 'event.dart';
import 'ticket_type.dart';

class Ticket {
  final int id;
  final String code;
  final String status;
  final Event? event;
  final TicketType? ticketType;

  Ticket({
    required this.id,
    required this.code,
    required this.status,
    this.event,
    this.ticketType,
  });

  factory Ticket.fromJson(Map<String, dynamic> json) => Ticket(
        id: json['id'] as int,
        code: json['code'] as String,
        status: json['status'] as String,
        event: json['event'] != null ? Event.fromJson(json['event'] as Map<String, dynamic>) : null,
        ticketType: json['ticket_type'] != null
            ? TicketType.fromJson(json['ticket_type'] as Map<String, dynamic>)
            : null,
      );
}
