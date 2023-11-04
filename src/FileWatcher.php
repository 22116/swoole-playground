<?php

namespace LsbProject\SwoolePlayground;

class FileWatcher
{
    public function __construct(
        private readonly mixed $inotifyInstance
    ) { }

    public function hasChanges(): bool
    {
        if (!$this->inotifyInstance) {
            throw new \RuntimeException('Watcher is not initialized');
        }

        return (bool) inotify_read($this->inotifyInstance);
    }
}
