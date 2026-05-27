<?php

namespace App\Request;

class LoginRequest
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
}