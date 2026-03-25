<?php


namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Species;
use App\Models\Pet;

class ConsultationImagesTest extends TestCase
{
    use RefreshDatabase;


    public function test_puede_agregar_imagenes_a_una_consulta()
    {
        $user = User::factory()->create();
        $species = Species::factory()->create();
        $pet = Pet::factory()->create(['species_id' => $species->id]);
        $consulta = \App\Models\Consultation::factory()->create([
            'species_id' => $species->id,
            'pet_id' => $pet->id,
        ]);
        $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($user)->post(route('consultations-add-images', $consulta), [
            'images' => [$file],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('consultation_images', [
            'consultation_id' => $consulta->id,
        ]);
    }

    public function test_no_permite_archivos_no_imagen()
    {
        $user = User::factory()->create();
        $species = Species::factory()->create();
        $pet = Pet::factory()->create(['species_id' => $species->id]);
        $consulta = \App\Models\Consultation::factory()->create([
            'species_id' => $species->id,
            'pet_id' => $pet->id,
        ]);
        $file = \Illuminate\Http\UploadedFile::fake()->create('test.txt', 10, 'text/plain');

        $response = $this->actingAs($user)->post(route('consultations-add-images', $consulta), [
            'images' => [$file],
        ]);

        $response->assertSessionHasErrors();
    }
}
