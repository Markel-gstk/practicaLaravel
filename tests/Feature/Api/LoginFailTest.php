<?php

use App\Models\User;
use function Pest\Laravel\postJson;


it("deniega el acceso si la contraseña es incorrecta", function () {
    // 1. Arrange: Creamos el usuario
    $user = User::factory()->create([
        "password" => bcrypt("123456789"),
    ]);

    // 2. Act: Intentamos entrar con una contraseña inventada
    $response = postJson("/api/login", [
        "email" => $user->email,
        "password" => "contraseña_falsa", // 👈 Clave incorrecta
    ]);

    // 3. Assert: Verificamos el rechazo
    $response->assertStatus(401); // 401 significa "No Autorizado"
    
    // Opcional: Verificar que el JSON devuelve un mensaje de error
    // (Ajusta "message" según lo que devuelva tu controlador real)
    $response->assertJson([
        "message" => "Credenciales incorrectas" 
    ]);
});




test('la verdad es verdadera', function () {
    expect(true)->toBeTrue();
});
