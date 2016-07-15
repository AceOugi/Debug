# Dumper - PHP Library

[![](https://img.shields.io/packagist/v/aceougi/dumper.svg)](https://packagist.org/packages/aceougi/dumper)
[![](https://img.shields.io/packagist/dt/aceougi/dumper.svg)](https://packagist.org/packages/aceougi/dumper)
[![](https://img.shields.io/packagist/l/aceougi/dumper.svg)](https://packagist.org/packages/aceougi/dumper)

Dumps information about a variable.

## Installation

```bash
$ composer require aceougi/dumper
```

## Usage

```php
dump($var);

dump($var1, $var2, $var3);
```

## Example

```php
$example = [
    'var10' => null,
    'var11' => false,
    'var12' => true,
    'var13' => 123,
    'var14' => 9.99,
    'var15' => 'Hello world!',
    'var21' => [],
    'var22' => [1 => 456, 789, 'end', 55.99],
    'var31' => fopen(__FILE__, 'r'),
    'var32' => ($tmp = fopen(__FILE__, 'r') AND fclose($tmp)) ? $tmp : $tmp,
    'var41' => new stdClass(),
    'var42' => new DateTime(),
];

dump($example);
```

## Screenshot

![](https://i.imgsafe.org/1f254e6.png)
