<?php

declare(strict_types=1);

namespace LsbProject\SwoolePlayground\Controller;

use Swoole\Http\Request;
use Swoole\Http\Response;

class BasicController
{
    public static function onRequest(Request $request, Response $response): void
    {
        $response->header('Content-Type', 'text/plain');
        $response->end("Hello World!\n");
    }
}
