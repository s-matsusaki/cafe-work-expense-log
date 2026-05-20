<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = env('OWNER_USER_NAME');
        $email = env('OWNER_USER_EMAIL');
        $password = env('OWNER_USER_PASSWORD');

        if (blank($name) || blank($email) || blank($password)) {
            throw new RuntimeException(
                'OWNER_USER_NAME, OWNER_USER_EMAIL, OWNER_USER_PASSWORD を設定してください。'
            );
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
            ]
        );
    }
}
