<?php
namespace Pod\Base\Service\Exception;

use Throwable;

class PodException extends \Exception
{

    // Error Codes
    const SDK_UNEXPECTED_ERROR_CODE = 888;
    const SERVER_ERROR_CODE = 500;
    const VALIDATION_ERROR_MESSAGE = '';
    private $_result;
    public function __construct($message = '', $code = 0, Throwable $previous = null, $result = [])
    {
        parent::__construct($message, $code, $previous);
        $this->_result = $result;
    }


    public function getResult() {
        $result = [
            'message'           => $this->getMessage(),
            'code'              => $this->getCode(),
            'originalResult'    => $this->_result
        ];
        return $result;
    }
}