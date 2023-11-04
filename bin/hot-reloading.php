<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use LsbProject\SwoolePlayground\Controller\BasicController;
use LsbProject\SwoolePlayground\Filesystem\HotReload;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$server = new Server($host, $ip);
$server->set([
    'open_tcp_keepalive' => 1,
    'tcp_keepidle' => 60, // in seconds, the idle time before sending keepalive probes
    'tcp_keepcount' => 5, // the number of keepalive probes to send before considering the connection dead
    'tcp_keepinterval' => 15, // the interval, in seconds, between individual keepalive probes
    'heartbeat_check_interval' => 60,
    'heartbeat_idle_time' => 600,
]);

$server->on('start', function (Server $server) use ($host, $ip) {
    echo "Swoole http server is started at http://$host:$ip\n";

    $hotReload = new HotReload();
    $fileWatcher = $hotReload->initialize();

    echo "File watcher is looking for a changes in {$hotReload->getFilePath()}\n";

    $server->tick(1000, function () use ($fileWatcher, $server) {
        if ($fileWatcher->hasChanges()) {
            echo "File modification detected, reloading the server.\n";

            $server->reload();
        }
    });
});

$server->on('request', function (Request $request, Response $response) {
    BasicController::onRequest($request, $response);
});

$server->start();
