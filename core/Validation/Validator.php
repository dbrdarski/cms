<?php

namespace Core\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;

    public function validateRequest($request, $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }
        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function validate($field, $rule, $value)
    {
        try {
            $rule->setName(ucfirst($field))->assert($value);
        } catch (NestedValidationException $e) {
            $this->errors = $e->getMessages();
        }
        $_SESSION['errors'] = $this->errors;

        return $this;
    }
    public function errors()
    {
        return $this->errors;
    }

    public function success()
    {
        return empty($this->errors);
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}