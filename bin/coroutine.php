<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Coroutine\System;

Co\run(static function () {
    Co\go(static function () {
        System::sleep(1);

        echo "Second message\n";
    });

    echo "First message\n";
});

Co\run(static function () {
    System::sleep(1);

    Co\go(static function () {
        System::sleep(1);

        echo "Fourth message\n";
    });

    echo "Third message\n";
});

echo "Fifth message\n";

Co\go(static function () {
    # Alternative to System::sleep(). Replaced by System::sleep() in coroutine on the fly.
    sleep(1);

    echo "Seven message\n";
});

Co\go(static function () {
    echo "Six message\n";
});

Swoole\Event::wait();
