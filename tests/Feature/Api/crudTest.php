<?php

use App\Models\Idea;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;


it('permite crear, actualizar y eliminar una idea', function () {
    /** @var User $user */
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $createResponse = postJson('/api/ideas', [
        'title' => 'Idea inicial',
        'description' => 'Descripción inicial de la idea',
    ]);

    $createResponse->assertCreated();

    assertDatabaseHas('ideas', [
        'title' => 'Idea inicial',
        'description' => 'Descripción inicial de la idea',
        'user_id' => $user->id,
    ]);

    /** @var Idea $idea */
    $idea = Idea::query()->firstWhere('title', 'Idea inicial');

    expect($idea)->not->toBeNull();

    $updateResponse = putJson("/api/ideas/{$idea->id}", [
        'title' => 'Idea actualizada',
        'description' => 'Descripción actualizada de la idea',
    ]);

    $updateResponse
        ->assertOk()
        ->assertJsonFragment([
            'title' => 'Idea actualizada',
        ]);

    $deleteResponse = deleteJson("/api/ideas/{$idea->id}");

    $deleteResponse->assertNoContent();

    assertDatabaseMissing('ideas', [
        'id' => $idea->id,
    ]);
});