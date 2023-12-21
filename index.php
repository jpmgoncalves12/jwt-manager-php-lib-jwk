<?php

require __DIR__ . '/vendor/autoload.php';

use JwtManager\JwtManager;

// openssl genrsa -out mykey.pem 1024
$privateKey = file_get_contents('mykey.pem');

$context = 'app-test';
$jwt = new JwtManager(
    $privateKey,
    $context,
    900,
    300,
    true
);

$jwtAppTest = $jwt->generate('myAud');
echo $jwtAppTest;

/* --------------- Generation Jwk to expose ------------------- */
echo PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;

// openssl rsa -in prod.pem -pubout > mykey.pub
$publicKey = file_get_contents('mykey.pub');

$keyInfo = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));
$jwk = [
    'keys' => [
        [
            'kty' => 'RSA',
            'n' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['n'])), '='),
            'e' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['e'])), '='),
        ],
    ],
];
echo json_encode($jwk);
