import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../services/auth_service.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _saving = false;
  String? _message;
  late final TextEditingController _nameController;

  @override
  void initState() {
    super.initState();
    final user = context.read<AuthService>().currentUser;
    _nameController = TextEditingController(text: user?.name ?? '');
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthService>();
    final user = auth.currentUser;

    return Scaffold(
      appBar: AppBar(title: const Text('Mi perfil')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          CircleAvatar(
            radius: 36,
            child: Text(
              (user?.name.isNotEmpty == true ? user!.name[0] : '?').toUpperCase(),
              style: const TextStyle(fontSize: 28),
            ),
          ),
          const SizedBox(height: 16),
          TextField(
            controller: _nameController,
            decoration: const InputDecoration(labelText: 'Nombre', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 12),
          TextField(
            enabled: false,
            controller: TextEditingController(text: user?.email ?? ''),
            decoration: const InputDecoration(labelText: 'Email', border: OutlineInputBorder()),
          ),
          if (_message != null) ...[
            const SizedBox(height: 12),
            Text(_message!, style: const TextStyle(color: Colors.green)),
          ],
          const SizedBox(height: 16),
          FilledButton(
            onPressed: _saving
                ? null
                : () async {
                    setState(() {
                      _saving = true;
                      _message = null;
                    });
                    try {
                      await context.read<AuthService>().updateProfile(name: _nameController.text.trim());
                      if (mounted) setState(() => _message = 'Perfil actualizado.');
                    } catch (_) {
                      if (mounted) setState(() => _message = null);
                    } finally {
                      if (mounted) setState(() => _saving = false);
                    }
                  },
            child: _saving
                ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2))
                : const Text('Guardar cambios'),
          ),
          const SizedBox(height: 24),
          OutlinedButton.icon(
            onPressed: () => context.read<AuthService>().logout(),
            icon: const Icon(Icons.logout),
            label: const Text('Cerrar sesión'),
          ),
        ],
      ),
    );
  }
}
