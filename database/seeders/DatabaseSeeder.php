<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@rent-erp.test'],
            [
                'name' => 'ERP Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => User::ROLE_ADMIN,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'legal@rent-erp.test'],
            [
                'name' => 'Legal Reviewer',
                'password' => Hash::make('password'),
                'role' => User::ROLE_LEGAL_REVIEWER,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'fleet@rent-erp.test'],
            [
                'name' => 'Fleet Manager',
                'password' => Hash::make('password'),
                'role' => User::ROLE_FLEET_MANAGER,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'finance@rent-erp.test'],
            [
                'name' => 'Finance Officer',
                'password' => Hash::make('password'),
                'role' => User::ROLE_FINANCE_OFFICER,
            ],
        );

        User::factory(3)->create();
    }
}
