import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:provider/provider.dart';
import '../../services/organizer_service.dart';

class ScanScreen extends StatefulWidget {
  const ScanScreen({super.key});

  @override
  State<ScanScreen> createState() => _ScanScreenState();
}

class _ScanScreenState extends State<ScanScreen> {
  final MobileScannerController _controller = MobileScannerController();
  bool _processing = false;
  String? _lastMessage;
  bool? _lastSuccess;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  Future<void> _onDetect(BarcodeCapture capture) async {
    if (_processing) return;

    final code = capture.barcodes.firstOrNull?.rawValue;
    if (code == null) return;

    setState(() {
      _processing = true;
      _lastMessage = null;
    });

    final result = await context.read<OrganizerService>().scan(code);

    if (!mounted) return;
    setState(() {
      _lastMessage = result.message;
      _lastSuccess = result.success;
    });

    await Future.delayed(const Duration(seconds: 2));
    if (mounted) setState(() => _processing = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Escanear entrada')),
      body: Stack(
        fit: StackFit.expand,
        children: [
          MobileScanner(controller: _controller, onDetect: _onDetect),
          Center(
            child: Container(
              width: 250,
              height: 250,
              decoration: BoxDecoration(
                border: Border.all(color: Colors.white, width: 3),
                borderRadius: BorderRadius.circular(16),
              ),
            ),
          ),
          if (_lastMessage != null)
            Positioned(
              left: 16,
              right: 16,
              bottom: 32,
              child: Material(
                color: _lastSuccess == true ? Colors.green.shade600 : Colors.red.shade600,
                borderRadius: BorderRadius.circular(12),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      Icon(_lastSuccess == true ? Icons.check_circle : Icons.error_outline, color: Colors.white),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(_lastMessage!, style: const TextStyle(color: Colors.white)),
                      ),
                    ],
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }
}

extension _FirstOrNull<T> on List<T> {
  T? get firstOrNull => isEmpty ? null : first;
}
