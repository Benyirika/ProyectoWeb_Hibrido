<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Paso 3: Límite de 5 intentos por minuto y registro detectivo en laravel.log
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        Log::warning('Login fallido', [
            'email' => $request->email,
            'ip'    => $request->ip(),
        ]);

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    return ['token' => $user->createToken('app')->plainTextToken];
})->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    // Correcta: solo los pedidos del usuario autenticado
    Route::get('/pedidos', fn (Request $r) => $r->user()->pedidos);

    // Paso 2: Control de acceso acotado al usuario autenticado (cierra IDOR)
    Route::get('/pedidos/{id}', fn (Request $r, $id) =>
        $r->user()->pedidos()->findOrFail($id));

    // Paso 2: Validación de entrada antes de guardar
    Route::post('/pedidos', function (Request $r) {
        $datos = $r->validate([
            'descripcion' => 'required|string|max:255',
            'total'       => 'required|numeric|min:0',
        ]);

        return $r->user()->pedidos()->create($datos);
    });

    // Paso 3: Revocar el token actual en el servidor al cerrar sesión
    Route::post('/logout', function (Request $r) {
        $r->user()->currentAccessToken()->delete();

        return response()->noContent();
    });
});