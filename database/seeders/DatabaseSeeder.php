<?php

namespace Database\Seeders;

use App\Models\User;
use Filament\Commands\MakeUserCommand as FilamentMakeUserCommand;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        $filamentMakeUserCommand = new FilamentMakeUserCommand();
        $reflector = new \ReflectionObject($filamentMakeUserCommand);

        $getUserModel = $reflector->getMethod('getUserModel');
        $getUserModel->setAccessible(true);
        $getUserModel->invoke($filamentMakeUserCommand)::create([
            'username' => 'superadmin',
            'password' => Hash::make('unpam'),
            'name' => 'Super Admin',
            'email' => 'superadmin@myemail.com'
        ]);
    }
}
