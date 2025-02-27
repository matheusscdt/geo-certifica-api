<?php

namespace App\Validators;

use Prettus\Validator\LaravelValidator;

abstract class BaseLaravelValidator extends LaravelValidator
{
    public function parserValidationRules($rules, $id = null)
    {
        if (null === $id) {
            return $rules;
        }

        array_walk($rules, function (&$rules, $field) use ($id) {
            if (!is_array($rules)) {
                $rules = explode("|", $rules);
            }

            foreach ($rules as $ruleIdx => $rule) {
                // get name and parameters
                if (!is_object($rule)) {
                    @list($name, $params) = array_pad(explode(":", $rule), 2, null);

                    // only do someting for the unique rule
                    if (strtolower($name) != "unique") {
                        continue; // continue in foreach loop, nothing left to do here
                    }

                    $p = array_map("trim", explode(",", $params));

                    // set field name to rules key ($field) (laravel convention)
                    if (!isset($p[1])) {
                        $p[1] = $field;
                    }

                    // set 3rd parameter to id given to getValidationRules()
                    $p[2] = $id;

                    $params = implode(",", $p);
                    $rules[$ruleIdx] = $name . ":" . $params;
                }
            }
        });

        return $rules;
    }
}
