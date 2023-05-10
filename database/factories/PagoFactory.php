<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PagoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'cliente_id' => $this->faker->randomNumber(),
            'total_cobro' => $this->faker->randomFloat(),
            'total_pagado' => $this->faker->randomFloat(),
            'observacion' => $this->faker->word(),
            'condicion' => $this->faker->word(),
            'notificacion' => $this->faker->word(),
            'diferencia' => $this->faker->randomFloat(),
            'fecha_aprobacion' => Carbon::now(),
            'estado' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
