<?php

namespace App\Http\Requests\API;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends ApiRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $this->email = strtolower($this->email);
        return [
            'firstname' => [
                'required',
                'regex:/^[^\$%\^*£=~@\d]+$/u',
                'min:3',
            ],
            'lastname' => [
                'required',
                'regex:/^[^\$%\^*£=~@\d]+$/u',
                'min:3',
            ],
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'required_with:password_confirmation|same:password_confirmation',
                'min:6',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            ],
            'confirmed_password' => 'required|same:password'
        ];
    }
}
