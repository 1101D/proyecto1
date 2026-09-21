import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../models/order.dart';
import '../../services/organizer_service.dart';

class OrdersScreen extends StatefulWidget {
  final int eventId;
  final String eventTitle;

  const OrdersScreen({super.key, required this.eventId, required this.eventTitle});

  @override
  State<OrdersScreen> createState() => _OrdersScreenState();
}

class _OrdersScreenState extends State<OrdersScreen> {
  List<OrganizerOrder> _orders = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final orders = await context.read<OrganizerService>().orders(widget.eventId);
      if (mounted) setState(() => _orders = orders);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final dateFormat = DateFormat('d MMM yyyy, HH:mm', 'es');

    return Scaffold(
      appBar: AppBar(title: Text('Pedidos · ${widget.eventTitle}')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _orders.isEmpty
              ? const Center(child: Text('Todavía no hay pedidos.'))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView.separated(
                    itemCount: _orders.length,
                    separatorBuilder: (_, _) => const Divider(height: 1),
                    itemBuilder: (context, index) {
                      final order = _orders[index];
                      return ListTile(
                        leading: CircleAvatar(
                          backgroundColor: order.status == 'paid' ? Colors.green.shade100 : Colors.grey.shade300,
                          child: const Icon(Icons.receipt_long_outlined, color: Colors.black87),
                        ),
                        title: Text('${order.userName} · ${order.ticketCount} entrada(s)'),
                        subtitle: Text('${order.userEmail} · ${dateFormat.format(order.createdAt)}'),
                        trailing: Text('\$${order.total.toStringAsFixed(0)}'),
                      );
                    },
                  ),
                ),
    );
  }
}
