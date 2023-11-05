<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Coroutine;
use Swoole\Coroutine\Server;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';

Co\run(static function () use ($host) {
    Co\go(static function () use ($host) {
        $server = new Server($host, 9501, false);
        $server->handle(static function (Server\Connection $connection) {
            $cid = Coroutine::getCid();

            while ($data = $connection->recv()) {
                $connection->send("Hello World from #$cid: $data\n");
            }

            $connection->close();
        });
        $server->start();
    });

    Co\go(static function () use ($host) {
        $server = new Server($host, 9502, false);
        $server->handle(static function (Server\Connection $connection) {
            $cid = Coroutine::getCid();

            while ($data = $connection->recv()) {
                $connection->send("Hello World from #$cid: $data\n");
            }

            $connection->close();
        });
        $server->start();
    });
});
