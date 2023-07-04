<?php

namespace App\Http\Requests\API\Projects;

use App\Http\Requests\ApiRequest;

class CreateProjectRequest extends ApiRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'name' => 'required|unique:projects,name',
            'description' => 'required',
        ];
     }
}
