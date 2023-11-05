<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Process;

$greetingText = "Hello text bypassed from parent process\n";
$process = new Process(static function (Process $worker) use ($greetingText) {
    echo $greetingText;
    echo "the pid of child process is " . $worker->pid . "\n";
    echo "the file descriptor of pipe is " . $worker->pipe . "\n";

    Co\go(static function () use ($worker) {
        sleep(1);

        $worker->write("Hello from child process\n");
    });

    $worker->write("Another text from child process\n");
}, enable_coroutine: true);

$process->start();

sleep(2);

# Hello from child process
echo $process->read();

# Another text from child process
echo $process->read();
