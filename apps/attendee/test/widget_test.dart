import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';

import 'package:attendee/screens/auth/login_screen.dart';
import 'package:attendee/services/api_client.dart';
import 'package:attendee/services/auth_service.dart';

void main() {
  testWidgets('Login screen shows the app name and form fields', (WidgetTester tester) async {
    final apiClient = ApiClient();

    await tester.pumpWidget(
      MultiProvider(
        providers: [
          Provider<ApiClient>.value(value: apiClient),
          ChangeNotifierProvider<AuthService>(create: (_) => AuthService(apiClient)),
        ],
        child: const MaterialApp(home: LoginScreen()),
      ),
    );

    expect(find.text('Eventz'), findsOneWidget);
    expect(find.text('Email'), findsOneWidget);
    expect(find.text('Contraseña'), findsOneWidget);
  });
}
