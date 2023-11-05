<?php

declare(strict_types=1);

use Swoole\Coroutine;


require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    $items = [];

    Coroutine\batch([
        static function () use (&$items) {
            sleep(3);
            $items[] = 'Foo';
        },
        static function () use (&$items) {
            sleep(5);
            $items[] = 'Bar';
        },
        static function () use (&$items) {
            sleep(1);
            $items[] = 'Baz';
        },
    ], 4);

    echo "Result: " . json_encode($items, JSON_PRETTY_PRINT) . PHP_EOL;

    /*
     *  === Output ===
     *
     * Result: [
     *    "Baz",
     *    "Foo",
     * ]
     */
});
