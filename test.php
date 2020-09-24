<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use JSONVerifier\Verifier;

$v = new Verifier;
$v->registerMappedPattern('date', '/\d{4}-\d{2}-\d{2}/', function($str) {
    return new \DateTimeImmutable($str);
});

$obj = $v->verifyObject([
    '?person' => ['null', [
        'name' => 'string',
        'age' => 'int',
        'foo' => '?bool',
        'pies' => 'number',
        'date_of_birth' => 'date'
    ]],
    'thing' => Verifier::arrayOf('int', 'string')
], [
    'person' => [
        'name' => 'Jason',
        'age' => 123,
        'foo' => null,
        'pies' => -1.23,
        'date_of_birth' => '1980-12-12'
    ],
    'thing' => [0, 1, 2, 'string']
]);

var_dump($obj);