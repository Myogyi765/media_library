<?php

namespace App\Validate;

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {

            $value = $data[$field] ?? null;

            if (($fieldRules['required'] ?? false) && empty($value)) {
                $errors[$field][] = "$field is required";
                continue;
            }

            if (isset($fieldRules['min']) && strlen($value) < $fieldRules['min']) {
                $errors[$field][] = "$field must be at least {$fieldRules['min']} characters";
            }

            if (isset($fieldRules['max']) && strlen($value) > $fieldRules['max']) {
                $errors[$field][] = "$field must be less than {$fieldRules['max']} characters";
            }

            if (($fieldRules['email'] ?? false) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "$field must be valid email";
            }



            if (isset($fieldRules['match']) && $value !== ($data[$fieldRules['match']] ?? null)) {
                $errors[$field][] = "$field must match {$fieldRules['match']}";
            }
        }

        return $errors;
    }
}