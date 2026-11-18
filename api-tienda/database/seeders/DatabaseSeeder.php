<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Pedido;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ana  = User::factory()->create(['email' => 'ana@test.pe',  'password' => Hash::make('secreto123')]);
        $beto = User::factory()->create(['email' => 'beto@test.pe', 'password' => Hash::make('secreto123')]);
        Pedido::create(['user_id' => $ana->id,  'descripcion' => 'Pedido de Ana',  'total' => 50]);
        Pedido::create(['user_id' => $beto->id, 'descripcion' => 'Pedido de Beto', 'total' => 80]);
    }
}