<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleName;
use App\User;

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
            [
                'name' => 'LIGL',
                'url' => 'https://lifeisgoodlabs.com',
            ],[
                'name' => 'DEMO',
                'url' => 'https://demo.com',
            ]
        ]);

        // LIGL Users
        User::create([
            'name' => 'Dasha',
            'email' => 'darina.levchuk@lifeisgoodlabs.com',
            'password' => Hash::make('demo'),
            'company_id' => 1,
        ])->assignRole(RoleName::SME);

        // DEMO Users
        // riv4ik@gmail.com
        User::create([
            'name' => 'Vadim Kukharenko',
            'email' => 'riv4ik@gmail.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::SME);

        // darina.levchuk@gmail.com
        User::create([
            'name' => 'D Levchuk',
            'email' => 'darina.levchuk@gmail.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::SME);

        // eugene.pyvovarov@gmail.com
        User::create([
            'name' => 'Eugene Pyvovarov',
            'email' => 'eugene.pyvovarov@gmail.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::SME);

        // John Doe
        User::create([
            'name' => 'John Doe',
            'email' => 'demo@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::SME);

        // SME - Subject Matter Expert
        User::create([
            'name' => 'SME',
            'email' => 'sme@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::SME);

        // AM - Accountable Manager
        User::create([
            'name' => 'AM',
            'email' => 'am@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::ACCOUNTABLE_MANAGER);

        // CMM - Compliance Monitoring Manager
        User::create([
            'name' => 'CMM',
            'email' => 'cmm@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::COMPLIANCE_MONITORING_MANAGER);

        // Auditor
        User::create([
            'name' => 'Auditor',
            'email' => 'auditor@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::AUDITOR);

        // Auditee (Nominated Person, NP)
        User::create([
            'name' => 'Auditee',
            'email' => 'auditee@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::AUDITEE);

        User::create([
            'name' => 'Auditee_2',
            'email' => 'auditee_2@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::AUDITEE);

        // Investigator
        User::create([
            'name' => 'Investigator',
            'email' => 'investigator@demo.com',
            'password' => Hash::make('demo'),
            'company_id' => 2,
        ])->assignRole(RoleName::INVESTIGATOR);


    }
}
