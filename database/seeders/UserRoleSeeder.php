<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{

    private $roles = [
        'Admin',
        'User',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $role) {
            $r = UserRole::where('name', $role)->first();
            if ($r == null) {
                $r = new UserRole();
            }
            $r->name = $role;
            $r->save();
        }
        UserRole::whereNotIn('name', $this->roles)->delete();
    }
}
