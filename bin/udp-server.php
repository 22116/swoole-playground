<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Server;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$server = new Server($host, $ip, Server::SIMPLE_MODE, Swoole\Constant::SOCK_UDP);

$server->on('start', function (Server $server) use ($host, $ip) {
    echo "Swoole TCP server is started at udp://$host:$ip\n";
});

$server->on('Packet', function (Server $server, string $data, array $clientInfo) {
    echo "Packet received from " . json_encode($clientInfo, JSON_PRETTY_PRINT) . ", data: $data" . PHP_EOL;

    $server->sendto($clientInfo['address'], $clientInfo['port'], "Server: " . $data);
});

$server->start();
