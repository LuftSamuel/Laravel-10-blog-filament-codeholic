<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\Models\User $adminUser */
        $adminUser = User::factory()->create([
            'email' => 'admin@admin.com',
            'name' => 'admin',
            'password' => bcrypt('admin123')
        ]);

        $adminRole = Role::create(['name' => 'admin']);

        $adminUser->assignRole($adminRole);

        // \App\Models\Post::factory(50)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
