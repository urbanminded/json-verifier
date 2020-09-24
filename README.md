# JSONVerifier

```php
use JSONVerifier\Verifier;

$schema = [
    // 'name' must be a string
    'name' => 'string',

    // 'age' is an optional key.
    // if present it must be an integer.
    '?age' => 'int',

    // 'favouriteFood' is an optional key.
    // if present it must be a string or null.
    '?favouriteFood' => '?string',

    // 'pets' must be an array of objects matching the
    // prescribed shape.
    'pets' => Verifier::arrayOf('string', [
        'name' => 'string',
        'species' => 'string'
    ])
];

$verifier = new Verifier();
$verifier->verifyObject($schema, [
    'name' => 'Jason',
    'favouriteFood' => null,
    'pets' => [
        'Dora',
        ['name' => 'Ruby', 'species' => 'cat'],
        ['name' => 'Byron', 'species' => 'cat'],
        ['name' => 'Mitzi', 'species' => 'cat'],
    ]
]);
```

## Installation

```shell
composer require urbanminded/json-verifier
```
