<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Server;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$server = new Server($host, $ip);
$server->set([
    'worker_num' => 4,   // The number of worker processes
    'daemonize' => false, // Whether to start as a daemon process
    'backlog' => 128,    // TCP backlog connection number
]);

$server->on('start', function (Server $server) use ($host, $ip) {
    echo "Swoole TCP server is started at tcp://$host:$ip\n";
});

$server->on('connect', function (Server $server, int $fd, int $reactorId) {
    echo "Client {$fd} ({$reactorId}) connect\n";
});

$server->on('receive', function(Server $server, int $fd, int $reactorId, string $data) {
    $server->send($fd, "Server: " . $data);
});

$server->on('close', function(Server $server, int $fd) {
    echo "Connection closed: {$fd}\n";
});

$server->start();
