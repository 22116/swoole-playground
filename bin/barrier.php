<?php

declare(strict_types=1);

use Swoole\Coroutine;


require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    $barrier = new Coroutine\Barrier();
    $items = [];

    Co\go(static function () use ($barrier, &$items) {
        sleep(3);
        $items[] = 'Foo';
    });

    Co\go(static function () use (&$items) {
        sleep(5);
        $items[] = 'Bar';
    });

    Co\go(static function () use ($barrier, &$items) {
        sleep(1);
        $items[] = 'Baz';
    });

    Coroutine\Barrier::wait($barrier);

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
