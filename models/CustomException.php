<?php
namespace Pod\Base\Service;
class CustomException extends \Exception
{

    private $_result;

    public function __construct($message,
                                $code = 0,
                                Exception $previous = null,
                                $result = [])
    {
        parent::__construct($message, $code, $previous);

        $this->_result = $result;
    }

    public function GetResult() {
        $result = [
            "message"           => $this->getMessage(),
            "code"              => $this->getCode(),
            "originalResult"    => $this->_result
        ];


        return $result;
    }
}