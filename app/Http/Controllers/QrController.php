<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\QrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * POST /api/qr/debug-generate
     * Endpoint de depuración para generar/actualizar el QR de un usuario
     * y devolver el resultado o el error detallado.
     */
    public function debugGenerate(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:user,id',
        ]);

        // ---- DIAGNÓSTICO DE QR_SECRET_KEY ----
        $envValue = env('QR_SECRET_KEY', '');
        $envLength = strlen($envValue);
        $envFirst = substr($envValue, 0, 10);

        $getenvValue = getenv('QR_SECRET_KEY');
        $getenvLength = $getenvValue !== false ? strlen($getenvValue) : 'no definida';
        $getenvFirst = $getenvValue !== false ? substr($getenvValue, 0, 10) : '';

        $serverValue = $_SERVER['QR_SECRET_KEY'] ?? 'no definida';
        $serverLength = $serverValue !== 'no definida' ? strlen($serverValue) : 'no definida';
        // ---------------------------------------

        try {
            $qrService = new QrService();   // aquí saltará el error si la clave es incorrecta
            $qrCode = $qrService->generateQrImage($request->input('user_id'));

            $user = User::find($request->input('user_id'));
            if ($user) {
                $user->updateQuietly(['codigo_qr' => $qrCode]);
            }

            return response()->json([
                'success' => true,
                'qr_length' => strlen($qrCode),
                'qr_preview' => substr($qrCode, 0, 100) . '...',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($e),
                'diagnostico' => [
                    'env' => [
                        'longitud' => $envLength,
                        'primeros10' => $envFirst,
                    ],
                    'getenv' => [
                        'longitud' => $getenvLength,
                        'primeros10' => $getenvFirst,
                    ],
                    '_SERVER' => [
                        'longitud' => $serverLength,
                    ],
                ],
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * POST /api/qr/regenerate-all
     * Regenera todos los QR de los usuarios (ACTIVOS).
     * Middleware: auth:sanctum, role:Administrador
     */
    public function regenerateAll(Request $request): JsonResponse
    {
        // Aumentar tiempo máximo a 5 minutos
        set_time_limit(300);

        $total = User::where('estado', 'ACTIVO')->count();
        $generados = 0;
        $errores = [];

        try {
            $qrService = new QrService();

            // Usamos cursor() en lugar de chunk para evitar problemas de hidratación
            // y reducir el consumo de memoria.
            $usuarios = User::where('estado', 'ACTIVO')->cursor();

            foreach ($usuarios as $user) {
                try {
                    $qrDataUri = $qrService->generateQrImage($user->id);

                    // Actualizar sin disparar eventos (updateQuietly no siempre funciona si no es Eloquent)
                    User::withoutEvents(function () use ($user, $qrDataUri) {
                        $user->update(['codigo_qr' => $qrDataUri]);
                    });

                    $generados++;
                } catch (\Throwable $e) {
                    $errores[] = [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'total_usuarios_activos' => $total,
                'qr_generados' => $generados,
                'errores' => $errores,
                'mensaje' => "Se regeneraron {$generados} QR de {$total} usuarios activos.",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}