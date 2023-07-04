<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ApiRequest. The Base-Class to validate API-Request
 * @package App\Http\Requests
 */
class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Called if the requestvalidation fails.
     * Overreite it to handle custom redirects or default responses or something like that.
     *
     *  @param Validator
     *  @return \Illuminate\Http\JsonResponse
     *
     */
    protected function failedValidation(Validator $validator) {
        $response = response()->json([
            'status' =>
                [
                    'code' => -1,
                    'msg' => $validator->getMessageBag()->first()
                ]
        ], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($response);
    }


    /**
     * Returns the ValidatorInstance. Used to return custom error messages, generated inside a controller
     *
     *  @return Validator
     */
    public function getValidationInstance() {
        return $this->getValidatorInstance();
    }
}
