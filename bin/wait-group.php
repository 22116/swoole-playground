<?php

declare(strict_types=1);

use Swoole\Coroutine;


require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    $wg = new Coroutine\WaitGroup();
    $items = [];

    Co\go(static function () use ($wg, &$items) {
        $wg->add();

        sleep(3);
        $items[] = 'Foo';

        $wg->done();
    });

    Co\go(static function () use ($wg, &$items) {
        $wg->add();

        sleep(5);
        $items[] = 'Bar';

        $wg->done();
    });

    Co\go(static function () use ($wg, &$items) {
        $wg->add();

        sleep(1);
        $items[] = 'Baz';

        $wg->done();
    });

    $wg->wait(4);

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
