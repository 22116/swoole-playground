<?php

namespace LsbProject\SwoolePlayground\Filesystem;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class HotReload
{
    public function __construct(private readonly string $filePath = __DIR__ . '/../..') { }

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
}
