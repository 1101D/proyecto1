import 'dart:async';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../models/event.dart';
import '../../services/event_service.dart';
import '../../widgets/event_card.dart';
import 'event_detail_screen.dart';

class EventListScreen extends StatefulWidget {
  const EventListScreen({super.key});

  @override
  State<EventListScreen> createState() => _EventListScreenState();
}

class _EventListScreenState extends State<EventListScreen> {
  final _searchController = TextEditingController();
  Timer? _debounce;

  List<Event> _events = [];
  List<String> _categories = [];
  String? _selectedCategory;
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadCategories();
    _search();
  }

  Future<void> _loadCategories() async {
    try {
      final categories = await context.read<EventService>().categories();
      if (mounted) setState(() => _categories = categories.keys.toList());
    } catch (_) {}
  }

  Future<void> _search() async {
    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      final events = await context.read<EventService>().list(
            query: _searchController.text.trim(),
            category: _selectedCategory,
          );
      if (mounted) setState(() => _events = events);
    } catch (_) {
      if (mounted) setState(() => _error = 'No pudimos cargar los eventos.');
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  void _onSearchChanged(String _) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 400), _search);
  }

  @override
  void dispose() {
    _debounce?.cancel();
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Explorar eventos'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
            child: TextField(
              controller: _searchController,
              onChanged: _onSearchChanged,
              decoration: InputDecoration(
                hintText: 'Buscar evento por nombre',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
          ),
          if (_categories.isNotEmpty)
            SizedBox(
              height: 44,
              child: ListView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 12),
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 4),
                    child: ChoiceChip(
                      label: const Text('Todas'),
                      selected: _selectedCategory == null,
                      onSelected: (_) {
                        setState(() => _selectedCategory = null);
                        _search();
                      },
                    ),
                  ),
                  ..._categories.map((category) => Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 4),
                        child: ChoiceChip(
                          label: Text(category),
                          selected: _selectedCategory == category,
                          onSelected: (_) {
                            setState(() => _selectedCategory = category);
                            _search();
                          },
                        ),
                      )),
                ],
              ),
            ),
          const SizedBox(height: 8),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _error != null
                    ? Center(child: Text(_error!))
                    : _events.isEmpty
                        ? const Center(child: Text('No se encontraron eventos.'))
                        : RefreshIndicator(
                            onRefresh: _search,
                            child: ListView.builder(
                              itemCount: _events.length,
                              itemBuilder: (context, index) {
                                final event = _events[index];
                                return EventCard(
                                  event: event,
                                  onTap: () async {
                                    await Navigator.of(context).push(
                                      MaterialPageRoute(builder: (_) => EventDetailScreen(eventId: event.id)),
                                    );
                                    _search();
                                  },
                                );
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }
}
