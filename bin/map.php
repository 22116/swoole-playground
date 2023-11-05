<?php

declare(strict_types=1);

use Swoole\Coroutine;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    $items = Coroutine\map([['Foo', 3], ['Bar', 5], ['Baz', 2]], static function (array $item) {
        sleep($item[1]);

        return $item[0];
    }, 4);

    echo "Result: " . json_encode($items, JSON_PRETTY_PRINT) . PHP_EOL;

    /*
     *  === Output ===
     *
     * Result: [
     *    "Foo",
     *    null,
     *    "Baz"
     * ]
     */
});
