<?php

use function Pest\Laravel\getJson;

it("bloquea el acceso a las ideas si el usuario no esta autenticado", function () {
    // Act: Intentamos acceder a la ruta protegida mediante GET
    // Nota: Ajusta la ruta a '/ideas' o '/api/ideas' según la tengas en routes/api.php
    $response = getJson('/api/ideas');

    // Assert: Verificamos que el middleware bloquee el paso y devuelva 401
    $response->assertStatus(401);
    
    // Opcional: También puedes verificar que el mensaje devuelto sea el estándar de Laravel
    $response->assertJson([
        'message' => 'Unauthenticated.'
    ]);
});