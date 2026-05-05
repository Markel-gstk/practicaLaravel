<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it("devuelve un error de validacion si el titulo de la idea esta vacio", function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson('/api/ideas', [
        'title' => '',
        'description' => 'Descripción válida',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});
