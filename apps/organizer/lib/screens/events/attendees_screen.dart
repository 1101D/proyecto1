import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/attendee.dart';
import '../../services/organizer_service.dart';

class AttendeesScreen extends StatefulWidget {
  final int eventId;
  final String eventTitle;

  const AttendeesScreen({super.key, required this.eventId, required this.eventTitle});

  @override
  State<AttendeesScreen> createState() => _AttendeesScreenState();
}

class _AttendeesScreenState extends State<AttendeesScreen> {
  List<Attendee> _attendees = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final attendees = await context.read<OrganizerService>().attendees(widget.eventId);
      if (mounted) setState(() => _attendees = attendees);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Asistentes · ${widget.eventTitle}')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _attendees.isEmpty
              ? const Center(child: Text('Todavía no hay asistentes.'))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView.separated(
                    itemCount: _attendees.length,
                    separatorBuilder: (_, _) => const Divider(height: 1),
                    itemBuilder: (context, index) {
                      final attendee = _attendees[index];
                      return ListTile(
                        leading: const CircleAvatar(child: Icon(Icons.person_outline)),
                        title: Text(attendee.userName),
                        subtitle: Text('${attendee.userEmail} · ${attendee.ticketTypeName}'),
                      );
                    },
                  ),
                ),
    );
  }
}
