<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 5/28/19
 * Time: 12:47 PM
 */
namespace Pod\Base\Service;
use Pod\Base\Service\Exception\InvalidConfigException;

class BaseInfo
{
    const PRODUCTION_SERVER = 'Production';
    const SANDBOX_SERVER = 'Sandbox';
    public static $serverType;
    public  $_token_;
    public  $_token_issuer_ = 1;


    public function setToken($token) {
        $this->_token_ = $token;
    }

    public function setTokenIssuer($tokenIssuer) {
        $this->_token_issuer_ = $tokenIssuer;
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getToken() {
        if ($this->_token_) {
            return $this->_token_;
        }
        else {
            throw new InvalidConfigException('_token_  is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    /**
     * @return int
     * @throws InvalidConfigException
     */
    public function getTokenIssuer() {
        if ($this->_token_issuer_) {
            return $this->_token_issuer_;
        }
        else {
            throw new InvalidConfigException('_token_issuer_ is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    public static function initServerType($serverType){
        self::$serverType = $serverType;
    }

}