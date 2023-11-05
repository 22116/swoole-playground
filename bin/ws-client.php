<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Coroutine;
use Swoole\Process;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$channel = new Coroutine\Channel(1);

Co\run(static function () use ($host, $ip, $channel) {
    $client = new Swoole\Coroutine\Http\Client($host, $ip);
    $client->upgrade('/');

    while ($channel->isEmpty()) {
        $client->push('Hello World!');
        $message = $client->recv();

        echo "Received data: " . json_encode($message, JSON_PRETTY_PRINT) . PHP_EOL;

        Coroutine::sleep(1);
    }

    $client->close();
});

Process::signal(SIGINT, static function () use ($channel) {
    echo "Stopping the client...\n";

    $channel->push(true);
});
