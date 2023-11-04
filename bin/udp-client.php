<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Client;
use Swoole\Constant;

$host = $_ENV['APP_HOST'] ??  '127.0.0.1';
$ip = (int) ($_ENV['APP_PORT'] ?? 9501);
$client = new Client(Constant::SOCK_UDP);

if (!$client->connect($host, $ip, -1)) {
    echo "connect failed. Error: {$client->errCode}. Check {$host}:{$ip}\n";
    exit;
}

while (true) {
    fwrite(STDOUT, 'Enter message: ');

    $msg = trim(fgets(STDIN));
    if ($msg === 'exit') {
        break;
    }

    $client->send($msg);

    $result = $client->recv();
    if (!$result) {
        echo "recv failed. Error: {$client->errCode}\n";
        break;
    }

    echo $result . PHP_EOL;
}
