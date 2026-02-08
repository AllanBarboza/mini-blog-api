<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'),
            'biography' => null,
            'banned_at' => null
        ];
    }

    public function banned(): static
    {
        return $this->state((fn() => [
            'banned_at' => now(),
        ]));
    }

    public function withUsername(string $username): static
    {
        return $this->state(fn() => [
            'username' => $username,
        ]);
    }

    public function withPassword(string $password): static
    {
        return $this->state(fn() => [
            'password' => Hash::make($password),
        ]);
    }
}
