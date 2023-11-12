<?php

declare(strict_types=1);

use Swoole\Process\Pool;
use Swoole\Timer;
use Swoole\Process;

$process = new Process(static function (Process $process) {
    $pool = new Pool(10, enable_coroutine: true);

    $pool->on('WorkerStart', static function (Pool $pool, int $workerId) {
        echo "Worker {$workerId} is started\n";

        Timer::tick(1000, static function () use ($workerId) {
            echo "Tick from #{$workerId}\n";
        });

        if ($workerId === 0) {
            Swoole\Timer::after(5000, static function () use ($pool) {
                echo "Stopping the pool...\n";

                $pool->stop();
                $pool->shutdown();

                echo "Pool is stopped\n";
            });
        }
    });

    $pool->start();

    echo "Pool is started\n";
});

$process->start();

Process::wait();
