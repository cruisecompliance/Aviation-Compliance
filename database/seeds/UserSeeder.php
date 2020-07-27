<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // companies
        DB::table('companies')->insert([
            'name' => 'LIGL',
            'url' => 'https://lifeisgoodlabs.com',

        ]);

        // Users
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'email' => 'demo@demo.com',
                'password' => Hash::make('demo'),
                'company_id' => 1,
            ],[
                'name' => 'Dasha',
                'email' => 'darina.levchuk@lifeisgoodlabs.com',
                'password' => Hash::make('demo'),
                'company_id' => 1,
            ]
        ]);

    }
}
