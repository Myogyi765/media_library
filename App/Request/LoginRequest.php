<?php

namespace App\Request;

use App\Validate\Validator;

class LoginRequest extends Validator
{
    public function rules(): array
    {
        return [
            'username_or_email' => [
                'required' => true,
            ],

            'password' => [
                'required' => true,
                'min' => 6,
            ],
        ];
    }
    public function validateRequest(array $data): array
    {
        return $this->validate($data, $this->rules());
    }
}