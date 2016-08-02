<?php

require_once __DIR__ . '/../vendor/autoload.php';
use bashkarev\r01\soap\Connection;

Connection::set(
    new Connection([
        'login' => 'testreseller',
        'password' => 'testreseller',
        'hdl' => 'TEST',
        'debug' => true,
        'options' => [
            'location' => '	https://partner.r01.ru:1443/partner_api.khtml',
            'uri' => 'urn:RegbaseSoapInterface',
            'exceptions' => true,
            'user_agent' => 'RegbaseSoapInterfaceClient',
            'trace' => 1,
        ]
    ])
);