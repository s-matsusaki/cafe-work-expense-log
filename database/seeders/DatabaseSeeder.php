<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->hasOwnerUserConfig()) {
            $this->call(OwnerUserSeeder::class);
        }

        $this->call([
            DemoUserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }

    private function hasOwnerUserConfig(): bool
    {
        return filled(env('OWNER_USER_NAME'))
            || filled(env('OWNER_USER_EMAIL'))
            || filled(env('OWNER_USER_PASSWORD'));
    }
}
