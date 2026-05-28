<?php

namespace App\Request;
use App\Validate\Validator;
class RegisterUserRequest extends Validator
{
    public function rules(): array
    {
        return [
            'username' => [
                'required' => true,
                'min' => 3,
                'max' => 50,
            ],

            'email' => [
                'required' => true,
                'email' => true,
                'max' => 100,
            ],

            'password' => [
                'required' => true,
                'min' => 8,
            ],

            'confirm_password' => [
                'required' => true,
                'match' => 'password',
            ],
        ];

    }

    
           public function validateRequest(array $data): array
    {
        return $this->validate($data, $this->rules());
    }
}