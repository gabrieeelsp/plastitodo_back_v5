<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'gabriel',
            'surname' => 'Picco',
            'email' => 'test@mail.com',
            'password' => bcrypt('secret999'),

            'ivacondition_id' => 1,
            'doctype_id' => 1,
            'docnumber' => '20458967939',
            'direccion' => 'Alicia morea de justo 6348',


        ]);
        DB::table('users')->insert([
            'name' => 'carolina',
            'surname' => 'Saavedra',
            'email' => 'caro@mail.com',
            'password' => bcrypt('secret999'),

        ]);

        DB::table('users')->insert([
            'name' => 'Astor',
            'surname' => 'Picco Saavedra',
            'email' => 'astor@mail.com',
            'password' => bcrypt('secret999'),

            'role' => 3,

            'ivacondition_id' => 2,
            'doctype_id' => 3,
            'docnumber' => '45896793',
            'direccion' => 'Alicia morea de justo 6348',


        ]);
    }
}
