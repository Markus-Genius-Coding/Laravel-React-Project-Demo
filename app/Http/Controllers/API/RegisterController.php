<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRegistrationRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller {

    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    /**
     * @OA\Post(
     * path="/api/registeruser",
     * summary="create a new user",
     * description="creates a new user",
     * operationId="registeruser",
     * tags={"RegisterController"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="userdata in requestbody",
     *          @OA\JsonContent(
     *               required={"salutation_id", "firstname","lastname", "email", "password", "confirmed_password"},
     *                @OA\Property(property="firstname", type="string", format="string", example="Max"),
     *                @OA\Property(property="lastname", type="string", format="string", example="Muster"),
     *                @OA\Property(property="email", type="string", format="string", example="max.muster@gmail.com"),
     *                @OA\Property(property="password", type="string", format="string", example="#hallo123+#+?"),
     *                @OA\Property(property="confirmed_password", type="string", format="string", example="#hallo123+#+?"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok.",
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

    public function registerUser(UserRegistrationRequest $request): JsonResponse {
        $this->userService->registerUser($request);
        return response()->json([], Response::HTTP_OK);
    }
}
