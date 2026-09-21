import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/event.dart';
import '../../services/api_client.dart';
import '../../services/ticket_service.dart';

class ReserveScreen extends StatefulWidget {
  final Event event;

  const ReserveScreen({super.key, required this.event});

  @override
  State<ReserveScreen> createState() => _ReserveScreenState();
}

class _ReserveScreenState extends State<ReserveScreen> {
  final Map<int, int> _quantities = {};
  bool _submitting = false;
  String? _error;

  double get _total {
    double total = 0;
    for (final tt in widget.event.ticketTypes) {
      total += tt.price * (_quantities[tt.id] ?? 0);
    }
    return total;
  }

  int get _totalTickets => _quantities.values.fold(0, (a, b) => a + b);

  Future<void> _submit() async {
    if (_totalTickets == 0) {
      setState(() => _error = 'Elegí al menos una entrada.');
      return;
    }

    setState(() {
      _submitting = true;
      _error = null;
    });

    try {
      await context.read<TicketService>().reserve(widget.event.id, _quantities);
      if (mounted) {
        Navigator.of(context).pop();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('¡Reserva confirmada! Ya tenés tus entradas.')),
        );
      }
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'No pudimos completar la reserva.');
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Reservar entradas')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Text(widget.event.title, style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          ...widget.event.ticketTypes.map((tt) {
            final qty = _quantities[tt.id] ?? 0;
            final max = tt.quantityAvailable.clamp(0, 20);

            return Card(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(tt.name, style: Theme.of(context).textTheme.titleMedium),
                          Text('\$${tt.price.toStringAsFixed(0)} · ${tt.quantityAvailable} disponibles'),
                        ],
                      ),
                    ),
                    IconButton(
                      onPressed: qty > 0 ? () => setState(() => _quantities[tt.id] = qty - 1) : null,
                      icon: const Icon(Icons.remove_circle_outline),
                    ),
                    Text('$qty', style: Theme.of(context).textTheme.titleMedium),
                    IconButton(
                      onPressed: qty < max ? () => setState(() => _quantities[tt.id] = qty + 1) : null,
                      icon: const Icon(Icons.add_circle_outline),
                    ),
                  ],
                ),
              ),
            );
          }),
          if (_error != null) ...[
            const SizedBox(height: 8),
            Text(_error!, style: const TextStyle(color: Colors.red)),
          ],
        ],
      ),
      bottomNavigationBar: SafeArea(
        minimum: const EdgeInsets.all(16),
        child: Row(
          children: [
            Expanded(
              child: Text('Total: \$${_total.toStringAsFixed(0)}', style: Theme.of(context).textTheme.titleMedium),
            ),
            FilledButton(
              onPressed: _submitting ? null : _submit,
              child: _submitting
                  ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2))
                  : const Text('Confirmar reserva'),
            ),
          ],
        ),
      ),
    );
  }
}
