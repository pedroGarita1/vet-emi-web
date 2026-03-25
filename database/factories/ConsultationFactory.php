<?php

namespace Database\Factories;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'species_id' => 1,
            'pet_id' => 1,
            'pet_name' => $this->faker->firstName,
            'species' => $this->faker->word,
            'owner_name' => $this->faker->name,
            'diagnosis' => $this->faker->sentence(3),
            'treatment' => $this->faker->sentence(6),
            'cost' => $this->faker->randomFloat(2, 100, 1000),
            'consulted_at' => now(),
        ];
    }
}
