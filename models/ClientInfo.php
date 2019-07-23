<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 5/28/19
 * Time: 12:47 PM
 */
namespace Pod\Base\Service;
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

    public  function getClientId() {
        if ($this->client_id) {
            return $this->client_id;
        }
        else {
            throw new Exception("Client Id is not set! Please set it and try again.", BaseService::VALIDATION_ERROR_CODE);
        }
    }

    public  function getClientSecret() {
        if ($this->client_secret) {
            return $this->client_secret;
        }
        else {
            throw new Exception("Client Secret is not set! Please set it and try again.", BaseService::VALIDATION_ERROR_CODE);
        }
    }

    public  function getRedirectUri() {
        if ($this->redirect_uri) {
            return $this->redirect_uri;
        }
        else {
            throw new Exception("Redirect Uri is not set! Please set it and try again.", BaseService::VALIDATION_ERROR_CODE);
        }
    }

}