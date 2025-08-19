<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\User\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // DB::table('categories')->delete();
        // $categories = ['Buy', 'Sale', 'Rent'];
        // foreach($categories as $category) {
        //     Category::create(['name' => $category]);
        // }

        // Post::factory(20)->create();

        //User::factory(120)->create();
        
        // $this->call([
        //     PermissionSeeder::class,
        //     SystemRoleSeeder::class
        // ]);
    }
}
