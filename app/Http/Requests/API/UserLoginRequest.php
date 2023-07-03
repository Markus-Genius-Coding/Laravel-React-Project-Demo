<?php

namespace App\Http\Requests\API;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required',
        ];
    }
}
