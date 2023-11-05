<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Swoole\Coroutine\Scheduler;

$scheduler = new Scheduler();
$scheduler->set([
    'max_coroutine' => 1000,
    'hook_flags' => SWOOLE_HOOK_ALL, # With this flag, we can use sleep() in coroutine
]);
$i = 0;

$scheduler->parallel(10, static function () use (&$i) {
    $current = $i++;

    sleep($i);

    echo "#$current message\n";
}, 1);

$scheduler->add(static function () {
    sleep(10);

    echo "Last message\n";
});

$scheduler->start();
