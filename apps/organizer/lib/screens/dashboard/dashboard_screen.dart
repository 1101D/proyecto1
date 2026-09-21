import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../models/organizer_event.dart';
import '../../services/auth_service.dart';
import '../../services/organizer_service.dart';
import '../events/event_detail_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  DashboardSummary? _summary;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final summary = await context.read<OrganizerService>().dashboard();
      if (mounted) setState(() => _summary = summary);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final summary = _summary;
    final dateFormat = DateFormat('d MMM yyyy, HH:mm', 'es');

    return Scaffold(
      appBar: AppBar(
        title: const Text('Panel de control'),
        actions: [
          IconButton(
            onPressed: () => context.read<AuthService>().logout(),
            icon: const Icon(Icons.logout),
            tooltip: 'Cerrar sesión',
          ),
        ],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : summary == null
              ? const Center(child: Text('No pudimos cargar el panel.'))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      GridView.count(
                        crossAxisCount: 2,
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        mainAxisSpacing: 12,
                        crossAxisSpacing: 12,
                        childAspectRatio: 1.6,
                        children: [
                          _SummaryCard(label: 'Eventos totales', value: '${summary.totalEvents}', icon: Icons.event),
                          _SummaryCard(label: 'Próximos eventos', value: '${summary.upcomingEvents}', icon: Icons.upcoming_outlined),
                          _SummaryCard(
                              label: 'Ventas brutas', value: '\$${summary.totalRevenue.toStringAsFixed(0)}', icon: Icons.payments_outlined),
                          _SummaryCard(
                              label: 'Entradas vendidas', value: '${summary.totalTicketsSold}', icon: Icons.confirmation_num_outlined),
                        ],
                      ),
                      const SizedBox(height: 24),
                      Text('Mis eventos', style: Theme.of(context).textTheme.titleMedium),
                      const SizedBox(height: 8),
                      if (summary.events.isEmpty) const Text('Todavía no creaste ningún evento.'),
                      ...summary.events.map((event) => Card(
                            child: ListTile(
                              title: Text(event.title),
                              subtitle: Text('${dateFormat.format(event.startAt)} · ${event.ticketsSoldCount} vendidas'),
                              trailing: const Icon(Icons.chevron_right),
                              onTap: () => Navigator.of(context).push(
                                MaterialPageRoute(builder: (_) => EventDetailScreen(event: event)),
                              ),
                            ),
                          )),
                    ],
                  ),
                ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  final String label;
  final String value;
  final IconData icon;

  const _SummaryCard({required this.label, required this.value, required this.icon});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: Theme.of(context).colorScheme.primary),
            const Spacer(),
            Text(value, style: Theme.of(context).textTheme.titleLarge),
            Text(label, style: Theme.of(context).textTheme.bodySmall),
          ],
        ),
      ),
    );
  }
}
