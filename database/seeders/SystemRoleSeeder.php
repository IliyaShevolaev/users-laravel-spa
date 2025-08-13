<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles\Role;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use Illuminate\Database\Seeder;
use App\Models\Roles\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();

        $systemRole = Role::create(['name' => 'system']);
        $systemRole->system = true;
        $systemRole->save();

        $systemRole->syncPermissions(Permission::all());

        $user = User::create([
            'name' => 'system',
            'email' => 'system@mail.com',
            'password' => '12345678',
            'gender' => GenderEnum::Male,
        ]);

        $user->syncRoles([$systemRole]);
    }
}
