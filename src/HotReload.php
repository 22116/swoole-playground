<?php

namespace Lsbproject\SwoolePlayground;

use OpenSwoole\Server;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class HotReload
{
    public function __construct(private readonly string $filePath = __DIR__ . '/..') { }

    public function initialize(): FileWatcher
    {
        $inotifyInstance = inotify_init();

        if (!$inotifyInstance) {
            throw new \RuntimeException('Cannot initialize file watcher');
        }

        inotify_add_watch($inotifyInstance, $this->filePath, IN_MODIFY | IN_CREATE | IN_DELETE);

        $items = new RecursiveDirectoryIterator($this->filePath);
        foreach (new RecursiveIteratorIterator($items) as $item) {
            if ($item->isDir()) {
                inotify_add_watch($inotifyInstance, $item->getRealPath(), IN_MODIFY | IN_CREATE | IN_DELETE);
            }
        }

        stream_set_blocking($inotifyInstance, false);

        return new FileWatcher($inotifyInstance);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public static function apply(Server $server, string $filePath = __DIR__ . '/..'): void
    {
        $hr = new self($filePath);
        $fileWatcher = $hr->initialize();

        echo "File watcher is looking for a changes in {$hr->getFilePath()}\n";

        $server->tick(1000, function () use ($fileWatcher, $server) {
            if ($fileWatcher->hasChanges()) {
                echo "File modification detected, reloading the server.\n";

                $server->reload();
            }
        });
    }
}
