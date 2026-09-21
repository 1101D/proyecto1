import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../models/ticket.dart';
import '../../services/ticket_service.dart';
import 'ticket_detail_screen.dart';

class MyTicketsScreen extends StatefulWidget {
  const MyTicketsScreen({super.key});

  @override
  State<MyTicketsScreen> createState() => _MyTicketsScreenState();
}

class _MyTicketsScreenState extends State<MyTicketsScreen> {
  List<Ticket> _tickets = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final tickets = await context.read<TicketService>().myTickets();
      if (mounted) setState(() => _tickets = tickets);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mis entradas')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _tickets.isEmpty
              ? const Center(child: Text('Todavía no reservaste ninguna entrada.'))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView.builder(
                    itemCount: _tickets.length,
                    itemBuilder: (context, index) {
                      final ticket = _tickets[index];
                      return ListTile(
                        leading: CircleAvatar(
                          backgroundColor: ticket.status == 'active' ? Colors.green.shade100 : Colors.grey.shade300,
                          child: const Icon(Icons.qr_code, color: Colors.black87),
                        ),
                        title: Text(ticket.event?.title ?? 'Evento'),
                        subtitle: Text(
                          '${ticket.ticketType?.name ?? ''} · ${ticket.event != null ? DateFormat('d MMM yyyy', 'es').format(ticket.event!.startAt) : ''}',
                        ),
                        trailing: const Icon(Icons.chevron_right),
                        onTap: () async {
                          await Navigator.of(context).push(
                            MaterialPageRoute(builder: (_) => TicketDetailScreen(ticket: ticket)),
                          );
                          _load();
                        },
                      );
                    },
                  ),
                ),
    );
  }
}
