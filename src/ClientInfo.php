<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 5/28/19
 * Time: 12:47 PM
 */
namespace Pod\Base\Service;
use Pod\Base\Service\Exception\InvalidConfigException;

class ClientInfo
{
    public  $client_id;
    public  $client_secret;
    public  $redirect_uri;

    public  function setClientId($client_id) {
        $this->client_id = $client_id;
    }

    public  function setClientSecret($client_secret) {
        $this->client_secret = $client_secret;
    }

    public  function setRedirectUri($redirect_uri) {
        $this->redirect_uri = $redirect_uri;

    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public  function getClientId() {
        if ($this->client_id) {
            return $this->client_id;
        }
        else {
            throw new InvalidConfigException('Client Id is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public  function getClientSecret() {
        if ($this->client_secret) {
            return $this->client_secret;
        }
        else {
            throw new InvalidConfigException('Client Secret is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public  function getRedirectUri() {
        if ($this->redirect_uri) {
            return $this->redirect_uri;
        }
        else {
            throw new InvalidConfigException('Redirect Uri is not set! Please set it and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
    }

}