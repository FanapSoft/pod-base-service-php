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
    private  $token;
    private  $tokenIssuer = 1;

    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getToken() {
        if ($this->token) {
            return $this->token;
        }
        else {
            throw new InvalidConfigException('token  is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    public function setTokenIssuer($tokenIssuer) {
        $this->$tokenIssuer = $tokenIssuer;
    }

    /**
     * @return int
     * @throws InvalidConfigException
     */
    public function getTokenIssuer() {
        if (isset($this->tokenIssuer)) {
            return $this->tokenIssuer;
        }
        else {
            throw new InvalidConfigException('_token_issuer_ is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    public static function initServerType($serverType){
        self::$serverType = $serverType;
    }

}