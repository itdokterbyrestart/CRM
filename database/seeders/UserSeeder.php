<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
};
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create user (role:admin) - Login: thomas@vhooft.nl - password
        User::create([
            'name' => 'Thomas van Hooft',
            'email' => 'thomas@vhooft.nl',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email_verified_at' => now(),
        ])->assignRole('admin');

        // Create user (role:user) - Login: joris@theiner.nl - password
        // User::create([
        //     'name' => 'Joris Theiner',
        //     'email' => 'joris@theiner.nl',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'email_verified_at' => now(),
        // ])->assignRole('user');


    }
}
