<?php

use Illuminate\Support\Facades\Route;

Route::get('/videojuegos', function () {
    return response()->json([
        'data' => [
            ['id' => 1, 'nombre' => 'The Legend of Zelda: Tears of the Kingdom', 'plataforma' => 'Nintendo Switch', 'precio' => 59.99],
            ['id' => 2, 'nombre' => 'God of War Ragnarök', 'plataforma' => 'PlayStation 5', 'precio' => 69.99],
            ['id' => 3, 'nombre' => 'Elden Ring', 'plataforma' => 'PC / Consolas', 'precio' => 49.99],
            ['id' => 4, 'nombre' => 'Cyberpunk 2077', 'plataforma' => 'PC', 'precio' => 39.99]
        ]
    ]);
});