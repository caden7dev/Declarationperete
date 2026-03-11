<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agents = [
            [
                'email' => 'cino@gmail.com',
                'first_name' => 'Helis',
                'last_name' => 'cino',
                'name' => 'Helis cino',
                'contact' => '22892998681',
                'address' => 'Lomé, Togo',
                'birth_date' => '2002-12-11',
                'gender' => 'male',
                'nationality' => 'togolaise',
            ],
            [
                'email' => 'agent2@gmail.com',
                'first_name' => 'Koffi',
                'last_name' => 'Mensah',
                'name' => 'Koffi Mensah',
                'contact' => '22890001122',
                'address' => 'Kara, Togo',
                'birth_date' => '1995-05-20',
                'gender' => 'male',
                'nationality' => 'togolaise',
            ],
            [
                'email' => 'agent3@gmail.com',
                'first_name' => 'Abla',
                'last_name' => 'Sika',
                'name' => 'Abla Sika',
                'contact' => '22891112233',
                'address' => 'Aného, Togo',
                'birth_date' => '1998-08-15',
                'gender' => 'female',
                'nationality' => 'togolaise',
            ],
            [
                'email' => 'agent4@gmail.com',
                'first_name' => 'Femi',
                'last_name' => 'Adeleke',
                'name' => 'Femi Adeleke',
                'contact' => '22893334455',
                'address' => 'Lomé, Togo',
                'birth_date' => '1990-01-30',
                'gender' => 'male',
                'nationality' => 'togolaise',
            ],
        ];

        foreach ($agents as $agentData) {
            User::firstOrCreate(
                ['email' => $agentData['email']],
                [
                    'first_name' => $agentData['first_name'],
                    'last_name'  => $agentData['last_name'],
                    'name'       => $agentData['name'],
                    'password'   => Hash::make('password123'),
                    'role'       => 'agent',
                    'contact'    => $agentData['contact'],
                    'address'    => $agentData['address'],
                    'birth_date' => $agentData['birth_date'],
                    'gender'     => $agentData['gender'],
                    'nationality'=> $agentData['nationality'],
                ]
            );
        }

        $this->command->info('✅ ' . count($agents) . ' agents ont été créés ou mis à jour avec succès !');
        $this->command->info('🔑 Mot de passe par défaut : password123');
    }
}