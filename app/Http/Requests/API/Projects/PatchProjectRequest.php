<?php

namespace App\Http\Requests\API\Projects;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;

class PatchProjectRequest extends ApiRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        $id = $this->route('id');
        return [
            'name' => 'required|unique:projects,name,'. $id,
            'description' => 'required',
        ];
    }
}
