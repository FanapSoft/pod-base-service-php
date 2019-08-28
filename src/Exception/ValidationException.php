<?php
/**
 * Created by PhpStorm.
 * User: reza
 * Date: 7/28/19
 * Time: 11:40 AM
 */

namespace Pod\Base\Service\Exception;


use Throwable;

class ValidationException extends PodException
{
    const VALIDATION_ERROR_CODE = 887;

    protected $validation_errors = [];

    public function __construct($validation_errors, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->validation_errors  = $validation_errors;
    }

    /**
     * give array of validation errors
     * @return array
     */
    public function getErrorsAsArray()
    {
        return $this->validation_errors;
    }

    /**
     * give string of validation errors
     * @return string
     */
    public function getErrorsAsString()
    {
        $errors = [];
        foreach ($this->validation_errors as $property => $property_errors) {
            $property_errors = is_array($property_errors) ? $property_errors : [$property_errors];

            $errors[] = implode(', ', $property_errors);
        }

        return implode(', ', $errors);
    }

}