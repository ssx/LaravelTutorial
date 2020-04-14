<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                TutorSeeder::class,
                ModuleSeeder::class,
                TutorialSeeder::class,
                TutorTutorialSeeder::class,
                UserSeeder::class,
                UserRolesSeeder::class
            ]
        );
    }
}
