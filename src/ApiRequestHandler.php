<?php
namespace Pod\Base\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Pod\Base\Service\Exception\PodException;
use Pod\Base\Service\Exception\RequestException;

Class ApiRequestHandler{
    /**
     * @param string $baseUri
     * @param string $method
     * @param string $relativeUri
     * @param array $option
     * @param bool $optionHasArray
     * @param bool $restFull
     *
     * @return mixed
     *
     * @throws RequestException
     * @throws PodException
     */
    public static function Request($baseUri, $method, $relativeUri, $option, $restFull = false, $optionHasArray = false) {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $baseUri,
            // You can set any number of default request options.
            'timeout'  => 30.0,
        ]);

        if ($optionHasArray && $method == 'GET') {
            $httpQuery = self::buildHttpQuery($option['query']);
            $relativeUri = $relativeUri . '?' . $httpQuery;
            unset($option['query']); // unset query because it is added to uri and dont need send again in query params
        }

        try {
            $response = $client->request($method, $relativeUri, $option);
        }
        catch (ClientException $e) {
            // echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                $code = $response->getStatusCode();
                $message = $response->getBody()->getContents();
                throw new RequestException($message, $code);
            }

            $code = RequestException::SERVER_CONNECTION_ERROR;
            $message  = 'Connection Interrupt! please try again later.';
            throw new RequestException($message, $code);
        } catch (GuzzleException $e) {
            throw new PodException($e->getMessage(), $e->getCode());
        }

        $result = json_decode($response->getBody()->getContents(), true);
//        $code = $response->getStatusCode();


        if (!$restFull) {
            if (isset($result['hasError']) && $result['hasError']) {
                throw new PodException($result['message'], $result['errorCode'],null, $result);
            }
        }

        return $result;
    }

    // build http query for array parameters
    public static function buildHttpQuery($params){
        $query = http_build_query($params ,null, '&');
        $httpQuery = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '[]=', $query);
        return $httpQuery;
    }
}