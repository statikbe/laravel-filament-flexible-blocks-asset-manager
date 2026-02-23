<?php

namespace Statikbe\FilamentFlexibleBlocksAssetManager\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
        ];
    }

    public function translatable(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => ['en' => $this->faker->sentence(3), 'nl' => $this->faker->sentence(3)],
        ]);
    }
}
