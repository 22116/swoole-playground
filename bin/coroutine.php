<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    Co\go(static function () {
        Swoole\Coroutine::sleep(1);

        echo "Second message\n";
    });

    echo "First message\n";
});

Co\run(static function () {
    Swoole\Coroutine::sleep(1);

    Co\go(static function () {
        Swoole\Coroutine::sleep(1);

        echo "Fourth message\n";
    });

    echo "Third message\n";
});

echo "Fifth message\n";

Co\go(static function () {
    echo "Six message\n";
});

Swoole\Event::wait();
