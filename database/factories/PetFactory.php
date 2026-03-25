<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'owner_name' => $this->faker->name,
            'species_id' => 1,
            'breed' => $this->faker->word,
            'size_category' => 'mediana',
            'is_active' => true,
        ];
    }
}
