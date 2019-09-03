<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 5/26/19
 * Time: 11:11 AM
 */
namespace Pod\Base\Service;
use JsonSchema\Validator;
use Pod\Base\Service\Exception\InvalidConfigException;
use Pod\Base\Service\Exception\ValidationException;

class BaseService
{
    protected static $serverType;
    protected static $config;
    protected static $jsonSchema;
    private static $validator;

    private static $noTrimFields = [
        'query' => true,
        'metaData' => true,
        'metadata' => true,
        'metaQuery' => true,
        'filterValue' => true,
        'client_metadata' => true,
    ];

    public function __construct() {
        self::$config = require __DIR__ . '/../config/baseUri.php';
        self::$serverType = self::getServerType();
        self::$validator = new Validator();
    }

    /**
     * validate options
     *
     * @param string $apiName
     * @param array $option
     * @param string $paramKey
     **
     * @throws ValidationException
     */
    public static function validateOption($apiName, $option, $paramKey = 'query') {
        $code = ValidationException::VALIDATION_ERROR_CODE;

        // header validation
        if (isset(self::$jsonSchema[$apiName]['header'])) {
            if(isset($option['headers'])) {
                $header = (object)$option['headers'];
                $headerSchema = json_decode( json_encode(self::$jsonSchema[$apiName]['header']) );
                self::$validator->validate($header, $headerSchema);
            } else {
                throw new ValidationException([], '"Headers" key in option should be set!', $code);
            }
        }
        elseif(isset($option['headers'])) {
            throw new ValidationException([], '"Headers" key in option is not allowed to send!', $code);
        }

        // parameter validation
        if (isset(self::$jsonSchema[$apiName][$paramKey])) {
            if (isset($option[$paramKey])){
                $params = (object)($option[$paramKey]);
                $paramSchema = json_decode( json_encode(self::$jsonSchema[$apiName][$paramKey]) );
                self::$validator->validate($params, $paramSchema);
            } else {
                throw new ValidationException([], "$paramKey key in option should be set!", $code);
            }
        }
        elseif(isset($option[$paramKey])) {
            throw new ValidationException([], "$paramKey key in option is not allowed to send!", $code);
        }

        // json parameter validation
        if (isset(self::$jsonSchema[$apiName]['json'])) {
            if (isset($option['json'])) {
                $jsonParams = isset($option['json']) ? (object)($option['json']) : [];
                $jsonParamSchema = json_decode(json_encode(self::$jsonSchema[$apiName]['json']));
                self::$validator->validate($jsonParams, $jsonParamSchema);
            } else
                {
                throw new ValidationException([], '"json" key in option should be set!', $code);
            }
        }
        elseif(isset($option['json'])) {
            throw new ValidationException([], "json key in option is not allowed to send!", $code);
        }


        if (!self::$validator->isValid()) {
            $errors = [];
            foreach (self::$validator->getErrors() as $error) {
                if (!($error['property'])) {
                    $error['property'] = $error['constraint'];
                }
                if (!isset($errors[$error['property']])){
                    $errors[$error['property']] = [];
                }
                $errors[$error['property']][] =  $error['message'];
            }
            throw new ValidationException($errors, 'Invalid Options', $code);
        }
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function getServerType() {
        $serverType = BaseInfo::$serverType;
        if ( !$serverType ||  # serverType is not set
            ($serverType != BaseInfo::PRODUCTION_SERVER && $serverType != BaseInfo::SANDBOX_SERVER) # serverType is not set to a proper value
        )
        {
            throw new InvalidConfigException('BaseInfo::$serverType variable is not set! or is not set to a proper value! Please set it to "Sandbox" or "Production" and try again.', InvalidConfigException::INVALID_CONFIG_PARAMETER);
        }
        return $serverType;
    }

    // build http query for array parameters
    public static function buildHttpQuery($params){

        $query = http_build_query($params ,null, '&');
        $httpQuery = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '[]=', $query);
        return $httpQuery;

    }

    public static function prepareData(&$value, $key) {
//        if (is_string($value) && !isset(self::$noTrimFields[$key])){
        if (is_string($value) && !array_key_exists($key, self::$noTrimFields)){
            $value = trim($value);
        }
        else if (is_bool($value)){
            $value = $value ? 'true' : 'false';
        }
    }
}