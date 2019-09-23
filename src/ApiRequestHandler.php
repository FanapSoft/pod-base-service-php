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

            $withoutBracketParams = isset($option['withoutBracketParams']) ? $option['withoutBracketParams'] : [];
            $withBracketParams = isset($option['withBracketParams']) ? $option['withBracketParams'] : [];

            $httpQuery = self::buildHttpQuery($withoutBracketParams, $withBracketParams);
            $relativeUri = $relativeUri . '?' . $httpQuery;
            unset($option['withoutBracketParams']); // unset query because it is added to uri and dont need send again in query params
            unset($option['withBracketParams']); // unset query because it is added to uri and dont need send again in query params
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
                if (!isset($result['message']) && isset($result['errorDescription'])) {
                    $result['message'] = $result['errorDescription'];
                    unset($result['errorDescription']);
                }
                $message = isset($result['message']) ? $result['message'] :"Some unhandled error has occurred!";
                $errorCode = isset($result['errorCode']) ? $result['errorCode'] : PodException::SERVER_UNHANDLED_ERROR_CODE;
                throw new PodException($message, $errorCode,null, $result);
            }
        }

        return $result;
    }

    // build http query for array and list parameters
    public static function buildHttpQuery($withoutBracketParams, $withBracketParams){

        $queryWithoutBracket = '';
        if (!empty($withoutBracketParams) ) {
            $queryWithoutBracket = http_build_query($withoutBracketParams ,null, '&');
            $queryWithoutBracket = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $queryWithoutBracket);

        }

        $queryWithBracket = '';
        if (!empty($withBracketParams) ) {
            $queryWithBracket = http_build_query($withBracketParams, null, '&');
            $queryWithBracket = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '[]=', $queryWithBracket);
            $queryWithoutBracket = !empty($queryWithoutBracket) ? $queryWithoutBracket .'&' : '';
        }

        return $queryWithoutBracket . $queryWithBracket;
    }
}