import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import 'package:qr_flutter/qr_flutter.dart';
import '../../models/ticket.dart';
import '../../services/ticket_service.dart';

class TicketDetailScreen extends StatefulWidget {
  final Ticket ticket;

  const TicketDetailScreen({super.key, required this.ticket});

  @override
  State<TicketDetailScreen> createState() => _TicketDetailScreenState();
}

class _TicketDetailScreenState extends State<TicketDetailScreen> {
  late Ticket _ticket;
  bool _cancelling = false;

  @override
  void initState() {
    super.initState();
    _ticket = widget.ticket;
  }

  Future<void> _cancel() async {
    final ticketService = context.read<TicketService>();
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Cancelar entrada'),
        content: const Text('¿Seguro que querés cancelar esta entrada? Esta acción no se puede deshacer.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('No')),
          TextButton(onPressed: () => Navigator.pop(context, true), child: const Text('Sí, cancelar')),
        ],
      ),
    );

    if (confirm != true) return;

    setState(() => _cancelling = true);
    try {
      final updated = await ticketService.cancel(_ticket.id);
      if (mounted) setState(() => _ticket = updated);
    } finally {
      if (mounted) setState(() => _cancelling = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final event = _ticket.event;
    final isActive = _ticket.status == 'active';

    return Scaffold(
      appBar: AppBar(title: Text(event?.title ?? 'Entrada')),
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    children: [
                      Opacity(
                        opacity: isActive ? 1 : 0.3,
                        child: QrImageView(data: _ticket.code, size: 220),
                      ),
                      const SizedBox(height: 16),
                      Text(_ticket.ticketType?.name ?? '', style: Theme.of(context).textTheme.titleMedium),
                      const SizedBox(height: 4),
                      Text(_ticket.code, style: const TextStyle(fontFamily: 'monospace', fontSize: 12)),
                      const SizedBox(height: 8),
                      Chip(
                        label: Text(_statusLabel(_ticket.status)),
                        backgroundColor: isActive ? Colors.green.shade100 : Colors.grey.shade300,
                      ),
                    ],
                  ),
                ),
              ),
              if (event != null) ...[
                const SizedBox(height: 16),
                Text(DateFormat('EEEE d MMMM yyyy, HH:mm', 'es').format(event.startAt)),
                if (event.address != null) Text(event.address!),
              ],
              if (isActive) ...[
                const SizedBox(height: 24),
                OutlinedButton(
                  onPressed: _cancelling ? null : _cancel,
                  child: _cancelling ? const CircularProgressIndicator(strokeWidth: 2) : const Text('Cancelar entrada'),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  String _statusLabel(String status) {
    switch (status) {
      case 'active':
        return 'Activa';
      case 'used':
        return 'Utilizada';
      case 'cancelled':
        return 'Cancelada';
      default:
        return status;
    }
  }
}
