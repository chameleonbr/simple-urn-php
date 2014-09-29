<?php

require "../vendor/autoload.php";

$urn = new SimpleURN();

$urn->registerUrnHandler('pr:cadicms', function($name, $val) {
    //implements some checks and validation
    return ['filtro' => 2, 'parametro' => $val];
});
$urn->registerUrnHandler('cnpj', function($name, $val) {
    return ['filtro' => 1, 'parametro' => $val];
});
$urn->registerUrnHandler('cnpjraiz', function($name, $val) {
    return ['filtro' => 3, 'parametro' => $val];
});
$urn->registerNotFoundHandler(function($name, $val) {
    return ['name' => $name, 'value' => $val];
});

// define URN string
$urnString = 'urn:pr:cadicms:1234567890';
var_dump($urn->handle($urnString));
echo '<br/>';
// define URN string
$urnString = 'urn:cnpj:1234567000123';
var_dump($urn->handle($urnString));
echo '<br/>';
// define URN string
$urnString = 'urn:cpf:12345678901';
var_dump($urn->handle($urnString));

echo '<br/>';
// define URN string
$urnString = '12345678901';
var_dump($urn->handle($urnString));

echo '<br/>';
// define URN string
$urnString = '';
var_dump($urn->handle($urnString));
