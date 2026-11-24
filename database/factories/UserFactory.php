<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => Role::where('nama_role', Role::KARYAWAN)->value('id'),
            'nama_lengkap' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= 'password',
            'status_aktif' => true,
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('nama_role', Role::OWNER)->value('id'),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_aktif' => false,
        ]);
    }
}
