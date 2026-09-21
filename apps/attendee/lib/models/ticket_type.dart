class TicketType {
  final int id;
  final String name;
  final double price;
  final int quantity;
  final int quantityAvailable;

  TicketType({
    required this.id,
    required this.name,
    required this.price,
    required this.quantity,
    required this.quantityAvailable,
  });

  factory TicketType.fromJson(Map<String, dynamic> json) => TicketType(
        id: json['id'] as int,
        name: json['name'] as String,
        price: double.parse(json['price'].toString()),
        quantity: json['quantity'] as int,
        quantityAvailable: json['quantity_available'] as int,
      );
}
