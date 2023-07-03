<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultUserSeeder extends Seeder {

    private const DEFAULT_EMAIL = 'testuser@mw-systems.com';

    /**
     * Run the database seeds.
     */
    public function run(): void {


        if (User::where('email', self::DEFAULT_EMAIL)->exists()) {
            return;
        }

        $userId = Str::uuid();
        while (User::where('id', $userId)->exists()) {
            //ensure the uuid is unique
            $userId = Str::uuid();
        }

        //create and store the new user
        $user = new User();
        $user->id = $userId;
        $user->firstname = 'Default';
        $user->lastname = 'Admin';
        $user->email = self::DEFAULT_EMAIL;
        $user->password = Hash::make('hallo123');
        $user->activation_code = User::getUniqueActivationCode();
        $user->email_verified_at = Carbon::now();
        $user->save();

        $permission = new UserPermission();
        $permission->user_id = $userId;
        $permission->permission_owner = $userId;
        $permission->role_id = UserRole::where('name', 'Admin')->first()->id;
        $permission->save();

    }
}
