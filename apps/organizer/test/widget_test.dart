import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';

import 'package:organizer/screens/auth/login_screen.dart';
import 'package:organizer/services/api_client.dart';
import 'package:organizer/services/auth_service.dart';

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

    expect(find.text('Eventz Organizador'), findsOneWidget);
    expect(find.text('Email'), findsOneWidget);
    expect(find.text('Contraseña'), findsOneWidget);
  });
}
