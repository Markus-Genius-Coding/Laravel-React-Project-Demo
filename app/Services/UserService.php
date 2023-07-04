<?php

namespace App\Services;

use App\Http\Requests\API\UserRegistrationRequest;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService {

    /**
     * @param string $email
     * @return User|null
     */
    public function findVerifiedUserByEmail(string $email) :? User {
        $user = User::where('email', $email)->whereNotNull('email_verified_at')->first();
        return $user;
    }

    /**
     * Create a new user with a default user role
     * @param UserRegistrationRequest $request
     * @return void
     */
    public function registerUser(UserRegistrationRequest $request) {
        $userId = Str::uuid();
        while (User::where('id', $userId)->exists()) {
            //ensure the uuid is unique
            $userId = Str::uuid();
        }

        //create and store the new user
        $request->email = strtolower($request->email);
        $user = new User();
        $user->id = $userId;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
        $user->activation_code = User::getUniqueActivationCode();
        //NOTE: Email should be verified by the user. this feature is nor implemented for the moment!
        $user->email_verified_at = Carbon::now();
        $user->save();

        $permission = new UserPermission();
        $permission->user_id = $userId;
        $permission->permission_owner = $userId;
        $permission->role_id = UserRole::where('name', 'User')->first()->id;
        $permission->save();
    }

}
