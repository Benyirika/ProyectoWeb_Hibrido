<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PedidoSeguridadTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_puede_ver_el_pedido_de_otro_usuario(): void
    {
        $ana  = User::factory()->create();
        $beto = User::factory()->create();
        $pedido = Pedido::create([
            'user_id'     => $beto->id,
            'descripcion' => 'Pedido de Beto',
            'total'       => 80,
        ]);

        Sanctum::actingAs($ana);
        $this->getJson("/api/pedidos/{$pedido->id}")->assertNotFound();
    }

    public function test_rechaza_un_pedido_sin_total(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $this->postJson('/api/pedidos', ['descripcion' => 'sin total'])
             ->assertUnprocessable();
    }
}