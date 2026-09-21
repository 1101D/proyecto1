import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'screens/auth/login_screen.dart';
import 'screens/home/home_screen.dart';
import 'services/api_client.dart';
import 'services/auth_service.dart';
import 'services/event_service.dart';
import 'services/ticket_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initializeDateFormatting('es');
  runApp(const EventzApp());
}

class EventzApp extends StatelessWidget {
  const EventzApp({super.key});

  @override
  Widget build(BuildContext context) {
    final apiClient = ApiClient();

    return MultiProvider(
      providers: [
        Provider<ApiClient>.value(value: apiClient),
        ChangeNotifierProvider<AuthService>(create: (_) => AuthService(apiClient)..bootstrap()),
        Provider<EventService>(create: (_) => EventService(apiClient)),
        Provider<TicketService>(create: (_) => TicketService(apiClient)),
      ],
      child: MaterialApp(
        title: 'Eventz',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(colorSchemeSeed: Colors.deepPurple, useMaterial3: true),
        home: const AuthGate(),
      ),
    );
  }
}

class AuthGate extends StatelessWidget {
  const AuthGate({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthService>();

    if (auth.isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return auth.isAuthenticated ? const HomeScreen() : const LoginScreen();
  }
}
