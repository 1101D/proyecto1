import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/event_dashboard.dart';
import '../../models/organizer_event.dart';
import '../../services/organizer_service.dart';
import 'attendees_screen.dart';
import 'orders_screen.dart';

class EventDetailScreen extends StatefulWidget {
  final OrganizerEvent event;

  const EventDetailScreen({super.key, required this.event});

  @override
  State<EventDetailScreen> createState() => _EventDetailScreenState();
}

class _EventDetailScreenState extends State<EventDetailScreen> {
  EventDashboard? _dashboard;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final dashboard = await context.read<OrganizerService>().eventDashboard(widget.event.id);
      if (mounted) setState(() => _dashboard = dashboard);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final dashboard = _dashboard;

    return Scaffold(
      appBar: AppBar(title: Text(widget.event.title, overflow: TextOverflow.ellipsis)),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : dashboard == null
              ? const Center(child: Text('No pudimos cargar los datos.'))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      Row(
                        children: [
                          Expanded(child: _StatCard(label: 'Ventas brutas', value: '\$${dashboard.revenue.toStringAsFixed(0)}')),
                          const SizedBox(width: 12),
                          Expanded(child: _StatCard(label: 'Entradas vendidas', value: '${dashboard.ticketsSold}')),
                        ],
                      ),
                      const SizedBox(height: 12),
                      _StatCard(label: 'Entradas canceladas', value: '${dashboard.ticketsCancelled}'),
                      const SizedBox(height: 24),
                      Text('Ventas por tipo de entrada', style: Theme.of(context).textTheme.titleMedium),
                      const SizedBox(height: 8),
                      ...dashboard.salesByType.map((tt) => Card(
                            child: ListTile(
                              title: Text(tt.name),
                              subtitle: Text('${tt.sold} / ${tt.quantity} vendidas'),
                              trailing: Text('\$${tt.revenue.toStringAsFixed(0)}'),
                            ),
                          )),
                      const SizedBox(height: 24),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton.icon(
                              icon: const Icon(Icons.people_outline),
                              label: const Text('Asistentes'),
                              onPressed: () => Navigator.of(context).push(
                                MaterialPageRoute(
                                  builder: (_) => AttendeesScreen(eventId: widget.event.id, eventTitle: widget.event.title),
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: OutlinedButton.icon(
                              icon: const Icon(Icons.receipt_long_outlined),
                              label: const Text('Pedidos'),
                              onPressed: () => Navigator.of(context).push(
                                MaterialPageRoute(
                                  builder: (_) => OrdersScreen(eventId: widget.event.id, eventTitle: widget.event.title),
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
    );
  }
}

class _StatCard extends StatelessWidget {
  final String label;
  final String value;

  const _StatCard({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 4),
            Text(value, style: Theme.of(context).textTheme.headlineSmall),
          ],
        ),
      ),
    );
  }
}
