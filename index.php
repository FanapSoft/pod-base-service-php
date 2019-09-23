<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 8/7/19
 * Time: 4:18 PM
 */
require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);
# required classes
use Pod\Base\Service\BaseService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;
putenv("SERVER_TYPE=".BaseService::PRODUCTION_SERVER);

$params = [
    '_token_' => '4d3d6b85e2e844b0ade83cc2ec5b4c85',
//    'name' => '',
    'offset' => 0,
    'size' => 10,
];
$BaseService = new BaseService();

try {
    $result = $BaseService->getGuildList($params);
    print_r($result);
}
catch (ValidationException $e) {
    print_r($e->errorsAsArray());
}
catch (PodException $e) {

    print_r(
        $e->GetResult()
    );
}

$params = [
    '_token_' => '4d3d6b85e2e844b0ade83cc2ec5b4c85',
];
try {
    $result = $BaseService->getCurrencyList($params);
    print_r($result);
}

catch (ValidationException $e) {
    print_r($e->errorsAsArray());
}
catch (PodException $e) {

    print_r(
        $e->GetResult()
    );
}
//die;

try {
    $result = $BaseService->getOtt($params);
    print_r($result);
}
catch (ValidationException $e) {
    print_r($e->errorsAsArray());
}
catch (PodException $e) {

    print_r(
        $e->GetResult()
    );
}