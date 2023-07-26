<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'juliana@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Juliana',
                'email' => 'juliana@gmail.com',
                'password' => Hash::make('Juliana123')
            ]);
        }

        $status = new Status();

        if (!Status::where('title', 'Em processamento')->exists()) {
            $status = new Status([
                'title' => 'Em processamento'
            ]);
            $status->save();
        }
        if (!Status::where('title', 'Falhou')->exists()) {
            $status = new Status([
                'title' => 'Falhou'
            ]);
            $status->save();
        }
        if (!Status::where('title', 'Finalizado')->exists()) {
            $status = new Status([
                'title' => 'Finalizado'
            ]);
            $status->save();
        }
    }
}
