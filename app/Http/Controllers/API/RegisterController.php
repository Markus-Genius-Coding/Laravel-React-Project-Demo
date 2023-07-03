<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRegistrationRequest;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/v1/registeruser",
     * summary="create a new user in HILDA-System",
     * description="creates a new user in HILDA-System",
     * operationId="registeruser",
     * tags={"users"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="userdata in requestbody",
     *          @OA\JsonContent(
     *               required={"salutation_id", "firstname","lastname", "email", "password", "confirmed_password"},
     *                @OA\Property(property="salutation_id", type="integer", example="1"),
     *                @OA\Property(property="firstname", type="string", format="string", example="Max"),
     *                @OA\Property(property="lastname", type="string", format="string", example="Muster"),
     *                @OA\Property(property="email", type="string", format="string", example="max.muster@gmail.com"),
     *                @OA\Property(property="password", type="string", format="string", example="#RudiHat35cm"),
     *                @OA\Property(property="confirmed_password", type="string", format="string", example="#RudiHat35cm"),
     *                @OA\Property(property="birthday", type="string", format="string", example="2000-02-05"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the status->code != 0 some error occurred.",
     *          @OA\JsonContent(
     *              example={"status":{"code":0,"msg":"OK"}}
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request, read the msg of the status object.",
     *          @OA\JsonContent(
     *              example={"status":{"code":-1,"msg":"email already taken."}}
     *          )
     *      )
     * )
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
        $user->email_verified_at = Carbon::now();
        $user->save();

        $permission = new UserPermission();
        $permission->user_id = $userId;
        $permission->permission_owner = $userId;
        $permission->role_id = UserRole::where('name', 'User')->first()->id;
        $permission->save();

        return response()->json([$user]);
    }
}
