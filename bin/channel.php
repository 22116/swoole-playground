<?php

declare(strict_types=1);

use Swoole\Coroutine;
use Swoole\Coroutine\Channel;
use Swoole\Timer;

require_once __DIR__ . '/../vendor/autoload.php';

Co\run(static function () {
    $channel = new Channel(1);

    Timer::tick(1000, static function () use ($channel,) {
        $cid = Coroutine::getCid();

        echo "[#$cid] Generating new number...\n";

        $channel->push(random_int(1, 100));
    });

    Co\go(static function () use ($channel) {
        while (true) {
            $cid = Coroutine::getCid();
            $number = $channel->pop();

            echo "[#$cid] Received number: {$number}\n";
        }
    });

    echo "Running a randomizer...\n";
});
