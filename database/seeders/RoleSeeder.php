<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name'=>'Administrator', 'slug'=>'administrator'],
            ['name'=>'User', 'slug'=>'user'],
            ['name'=>'Editor', 'slug'=>'editor'],
            ['name'=>'Customer', 'slug'=>'customer']
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}
