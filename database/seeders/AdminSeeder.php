<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- ici la bonne importation
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'email' => 'ahadzicaden@gmail.com',
                'first_name' => 'Admin',
                'last_name' => 'Principal',
                'name' => 'Admin Principal',
            ],
            [
                'email' => 'enochkoukpaki@gmail.com',
                'first_name' => 'Enoch',
                'last_name' => 'Koukpaki',
                'name' => 'Koukpaki Enoch',
            ],
            // Ajout des 3 nouveaux administrateurs
            [
                'email' => 'konlaniblaise@gmail.com',
                'first_name' => 'Blaise',
                'last_name' => 'Konlani',
                'name' => 'konlani blaise',
            ],
            [
                'email' => 'amegnranmartin@gmail.com',
                'first_name' => 'Martin',
                'last_name' => 'Amegnran',
                'name' => 'Amegnran Martin',
            ],
            [
                'email' => 'tamakamarcel@gmail.com',
                'first_name' => 'Marcel',
                'last_name' => 'Tamaka',
                'name' => 'Tamaka Marcel',
            ],
        ];

        foreach ($admins as $adminData) {
            User::firstOrCreate(
                ['email' => $adminData['email']], // Condition d'unicité
                [
                    'first_name' => $adminData['first_name'],
                    'last_name'  => $adminData['last_name'],
                    'name'       => $adminData['name'],
                    'password'   => Hash::make('admin123'), // Mot de passe commun
                    'role'       => 'admin',
                ]
            );
        }
    }
}