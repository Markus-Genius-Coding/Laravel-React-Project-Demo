<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserLoginRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller {

    private UserService $userService;
    private AuthService $authService;

    public function __construct() {
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }

    /**
     * @OA\Post(
     * path="/api/user/login",
     * summary="Login a user",
     * description="Login a user",
     * operationId="userlogin",
     * tags={"AuthController"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *               required={"email", "password"},
     *                @OA\Property(property="email", type="string", format="string", example="hallo@hi.servus"),
     *                @OA\Property(property="password", type="string", format="string", example="mySecurePassword"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="If the status->code != 0 some error occurred.",
     *          @OA\JsonContent(
     *              example={"authdata": {
     *                          "token_type": "Bearer",
     *                          "expires_in": 3600,
     *                          "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2RhNzI2Ny1hZjA5LTQxZDItYjM4My02ZDY1NGYyNzgyZjYiLCJqdGkiOiIwOTcwMDI0ZTcxYjBmOGE4YTBiMjIwMzU1NDkxNDkyZjE4YzIzOTAzNjg5YTQ5MTIwNTY1NGM2YTJjOWI4NWEyZGQ2MzRkOWE1YWFlMjRhMCIsImlhdCI6MTYyNTY2NjAwNSwibmJmIjoxNjI1NjY2MDA1LCJleHAiOjE2MjU2Njk2MDUsInN1YiI6IjU5NTQxNWUxLWZmZTMtNDIxZC05NTU3LTJiOTU0ODFmYjRlMSIsInNjb3BlcyI6WyIqIl19.QWInHl2bGMx4o1gEFGWGyxDVURx3hGr8DmmhSXBbZ4Ecylq8UXnn08ktBGuF2cDSLxkPgr3mHmlRuSUNP0yyovPKDdMJg8oVUfogkMBewfU2BZIOPhhp7fZjruPUrkZBfxH0NyaC6YtUpG780NmAHJzwXuqtooekNmDzQrCGifztYMQsz3Vv2bpEfEVIt4J177bKDzulkeWklDehqyJ0Qx_3ZN5JLrYjDuR_sdif9q85uQZH5ebBu7v5qg0jcfimzmobj_-sH0TsmWwUybkqWM_MgtITK-uFDR8B43FSGfChNVVbE1Qu1_Puwc7B4IS7NCcm58IdHEF7H4KNuSBEQ-Z5X6xwWB-MfdCrDSbtd_N-zu0RCd7e7Qy8lGAhWGofWgKpRE5zgUjM10ze608ssKPpvXlIqWQqdVRDXRRAPsncayT5W6q3NNk4_qmubgaff3r5-25js4ccNTLAAbayxzzqViZcHfPOzRPl8Kt7Cgbf4iJHoEByXeF4QeVdsUks0Gi9oQ_a6ZClOaXF7P73IdMQRW5wUQ175CYhhc-R9YGJ0B-5ob0SbcMX9L8PWJujrbmRCx2U1JieDZPWEqqBOqDpLuXrWWrnXM2GabZhyel2lGuzZiLkYLfIGA43MoFcW1LXwt0SPk2HPI_m09C_MFFE1_WyPAOFM4Lb6MgGP2s", "refresh_token": "def50200767515ec9735fe3639a67106528492f861713336aafed3a5826d62371abdfe3481cd946e1909d5561514f077f9c82bc20ddf5d059a222f54437cdcc22825666ff296f4a3e55c4bd0b0b5f3bec4e14d3c5c0ab770a5b468c0a64e69ed28a0a1f8b525bba66c18967220732629564eb0b9e62ab65728ef3fc6893d9f93a3049a9e6181cac13020a194032f37a6265581495057068d735de0324718ee77054df74dc195830edbb14246cdd45f10635d942d45533ea8ac96cc7de62c3a0cebc7161631ea0c6194cb38c48eab7791e3b5e45a889f49ccbe41bee86f4e1359a56f510a2fe51c018172765ad638fa28fe89241793c0766596ef1db2a147a9ebbbc5ebff8a91e409c18fa739f9c6e4a4f16fe240a43ffb60287ce2f879db11e5e35a283ac7896e4fa3d7d5e069a4363fbc85ed59dfcd28af7e2691d45b78a755642299eb69c786415253fefe195a574f0ee4495d7df6e18cdb95bc9062cac73be7495b452e65e032af9956ca87361de012fbb57c505e2ad5e0c14be534343322dbd40da5e94a5b96bad7d5915fb2b65dd5c71d398c6ab6437a411077b6e8860a3406578d0f2895516951043e",
     *                          "userdata": {
     *                              "email": "wilhelm@mw-systems.com",
     *                              "email_verified_at": "2022-01-12T13:00:00.000000Z",
     *                              "created_at": "2021-09-03T13:35:21.000000Z",
     *                              "updated_at": "2021-09-03T13:35:22.000000Z",
     *                              "firstname": "Markus",
     *                              "lastname": "Wilhelm",
     *                              "activation_code": "Tk3SwcCTIy2rTTQCAy4mB0kttAtF2a1630676122",
     *                              "id": "e517c4b1-b6f6-4e5b-8eed-6c638f0b1100",
     *                           },
     *                      }}
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request, read the msg of the status object.",
     *          @OA\JsonContent(
     *              example={"status":{"code":-1,"msg":"error message here"}}
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      )
     * )
     */

    public function doLogin(UserLoginRequest $request) {

        $request['email'] = strtolower($request->email);
        $user = $this->userService->findVerifiedUserByEmail($request->email);
        if ($user == null || !Auth::attempt($request->toArray())) {
            return response()->json([], Response::HTTP_UNAUTHORIZED);
        } else {
            $this->authService->revokeAccessAndRefreshTokens($user->id);
            $result = [
                'authdata' => $this->authService->getTokenAndRefreshToken(strtolower($request->email), $request->password)->original,
                'userdata' => $user,
            ];

            return response()->json($result, Response::HTTP_OK);
        }
    }

}
