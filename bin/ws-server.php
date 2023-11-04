<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$server = new Server($host, $ip);

$server->on('start', function (Server $server) use ($host, $ip) {
    echo "Swoole WebSocket server is started at ws://$host:$ip\n";
});

$server->on('open', function (Server $server, Request $request) {
    echo "Connection open: {$request->fd}\n";
});

$server->on('message', function (Server $server, Frame $frame) {
    echo "Received message: {$frame->data}\n";

    $server->push($frame->fd, "Server: {$frame->data}");
});

$server->on('close', function(Server $server, int $fd) {
    echo "Connection closed: {$fd}\n";
});

$server->on('disconnect', function (Server $server, int $fd) {
    echo "Connection disconnected: {$fd}\n";
});

$server->start();
