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
        // User::factory(10)->create();

        // Core seeders
        $this->call([
            DepartmentSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            IssueTypeSeeder::class,
            AffectedSystemSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
