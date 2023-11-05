<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Process;
use Swoole\Timer;

$processes = [];
foreach (range(1, 5) as $item) {
    $process = new Process(static function (Process $worker) {
        while (true) {
            $message = $worker->pop();

            if ($message === 'exit') {
                echo "Child process {$worker->pid} exited\n";
                break;
            }

            echo "From child process {$worker->pid} : {$message}";
        }
    }, enable_coroutine: true);

    $process->useQueue();
    $pid = $process->start();

    $processes[$pid] = $process;
}

foreach($processes as $pid => $process) {
    $process->push("Hello #{$pid}\n");
}

Co\run(static function () use ($processes) {
    Timer::after(2000, static function () use ($processes) {
        echo "Stopping all child processes...\n";

        foreach($processes as $process) {
            $process->push('exit');
        }
    });
});


Process::wait();

echo "Parent process exited\n";
