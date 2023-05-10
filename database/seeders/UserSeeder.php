<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User Demo',
            'email' => 'correo@correo.com',
            'rol' => 'Administrador',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'remember_token' => Str::random(10),
            'identificador' => 'demo',
            'profile_photo_path' => 'logo_facturas.png', 
            'estado' => '1',
        ])->assignRole('Administrador');
    }
}