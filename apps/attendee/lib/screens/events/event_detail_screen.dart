import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';
import '../../models/event.dart';
import '../../services/auth_service.dart';
import '../../services/event_service.dart';
import '../auth/login_screen.dart';
import 'reserve_screen.dart';

class EventDetailScreen extends StatefulWidget {
  final int eventId;

  const EventDetailScreen({super.key, required this.eventId});

  @override
  State<EventDetailScreen> createState() => _EventDetailScreenState();
}

class _EventDetailScreenState extends State<EventDetailScreen> {
  Event? _event;
  bool _loading = true;
  bool _savingLike = false;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final event = await context.read<EventService>().show(widget.eventId);
      if (mounted) setState(() => _event = event);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<bool> _requireLogin() async {
    if (context.read<AuthService>().isAuthenticated) return true;

    await Navigator.of(context).push(MaterialPageRoute(builder: (_) => const LoginScreen()));
    if (!mounted) return false;
    return context.read<AuthService>().isAuthenticated;
  }

  Future<void> _toggleLike() async {
    final event = _event;
    if (event == null || _savingLike) return;
    final service = context.read<EventService>();
    if (!await _requireLogin()) return;
    if (!mounted) return;

    setState(() => _savingLike = true);
    try {
      if (event.isSaved) {
        await service.unlike(event.id);
      } else {
        await service.like(event.id);
      }
      await _load();
    } finally {
      if (mounted) setState(() => _savingLike = false);
    }
  }

  void _share() {
    final event = _event;
    if (event == null) return;
    SharePlus.instance.share(ShareParams(
      text: '¡Mirá este evento! ${event.title} — ${DateFormat('d MMM yyyy, HH:mm', 'es').format(event.startAt)}',
    ));
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    final event = _event;
    if (event == null) {
      return const Scaffold(body: Center(child: Text('Evento no encontrado.')));
    }

    final dateFormat = DateFormat('EEEE d MMMM yyyy, HH:mm', 'es');

    return Scaffold(
      appBar: AppBar(
        title: Text(event.title, overflow: TextOverflow.ellipsis),
        actions: [
          IconButton(onPressed: _share, icon: const Icon(Icons.share_outlined)),
          IconButton(
            onPressed: _savingLike ? null : _toggleLike,
            icon: Icon(event.isSaved ? Icons.favorite : Icons.favorite_border,
                color: event.isSaved ? Colors.red : null),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          if (event.category != null)
            Chip(label: Text('${event.category}${event.subcategory != null ? ' · ${event.subcategory}' : ''}')),
          const SizedBox(height: 12),
          Text(event.title, style: Theme.of(context).textTheme.headlineSmall),
          const SizedBox(height: 8),
          Row(
            children: [
              const Icon(Icons.calendar_today, size: 18),
              const SizedBox(width: 8),
              Expanded(child: Text(dateFormat.format(event.startAt))),
            ],
          ),
          if (event.address != null) ...[
            const SizedBox(height: 8),
            Row(
              children: [
                const Icon(Icons.location_on_outlined, size: 18),
                const SizedBox(width: 8),
                Expanded(child: Text(event.address!)),
              ],
            ),
          ],
          const SizedBox(height: 16),
          if (event.description != null) ...[
            Text('Sobre el evento', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(event.description!),
            const SizedBox(height: 16),
          ],
          Text('Entradas', style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 8),
          if (event.ticketTypes.isEmpty)
            const Text('No hay entradas disponibles por ahora.')
          else
            ...event.ticketTypes.map((tt) => ListTile(
                  contentPadding: EdgeInsets.zero,
                  title: Text(tt.name),
                  subtitle: Text('${tt.quantityAvailable} disponibles'),
                  trailing: Text('\$${tt.price.toStringAsFixed(0)}'),
                )),
        ],
      ),
      bottomNavigationBar: SafeArea(
        minimum: const EdgeInsets.all(16),
        child: FilledButton(
          onPressed: event.ticketTypes.isEmpty
              ? null
              : () async {
                  if (!await _requireLogin()) return;
                  if (!context.mounted) return;
                  await Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => ReserveScreen(event: event)),
                  );
                  _load();
                },
          child: const Text('Reservar entradas'),
        ),
      ),
    );
  }
}
