import 'ticket_type.dart';

class Event {
  final int id;
  final String title;
  final String slug;
  final String? description;
  final String? category;
  final String? subcategory;
  final String? coverImage;
  final String? address;
  final DateTime startAt;
  final DateTime? endAt;
  final bool isSaved;
  final List<TicketType> ticketTypes;

  Event({
    required this.id,
    required this.title,
    required this.slug,
    this.description,
    this.category,
    this.subcategory,
    this.coverImage,
    this.address,
    required this.startAt,
    this.endAt,
    this.isSaved = false,
    this.ticketTypes = const [],
  });

  factory Event.fromJson(Map<String, dynamic> json) => Event(
        id: json['id'] as int,
        title: json['title'] as String,
        slug: json['slug'] as String,
        description: json['description'] as String?,
        category: json['category'] as String?,
        subcategory: json['subcategory'] as String?,
        coverImage: json['cover_image'] as String?,
        address: json['address'] as String?,
        startAt: DateTime.parse(json['start_at'] as String),
        endAt: json['end_at'] != null ? DateTime.parse(json['end_at'] as String) : null,
        isSaved: json['is_saved'] as bool? ?? false,
        ticketTypes: (json['ticket_types'] as List<dynamic>?)
                ?.map((e) => TicketType.fromJson(e as Map<String, dynamic>))
                .toList() ??
            const [],
      );
}
