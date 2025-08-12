<?php

namespace Database\Seeders;

use App\Models\Roles\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::truncate();

        foreach (config('permissions') as $entityName => $entity) {
            foreach ($entity as $action) {
                Permission::create([
                    'name' => $entityName . '-' . $action
                ]);
            }
        }
    }
}
