<?php
use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Login SIN límite de intentos (vulnerable)
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }
    return ['token' => $user->createToken('app')->plainTextToken];
});

Route::middleware('auth:sanctum')->group(function () {
    // Correcta: solo los pedidos del usuario autenticado
    Route::get('/pedidos', fn (Request $r) => $r->user()->pedidos);

    // VULNERABLE: no verifica quién es el dueño (IDOR)
    Route::get('/pedidos/{id}', fn ($id) => Pedido::findOrFail($id));

    // VULNERABLE: guarda sin validar los datos
    Route::post('/pedidos', fn (Request $r) =>
        $r->user()->pedidos()->create($r->only('descripcion', 'total')));
});