<?php

namespace Outpost\Cache\Events;

class ItemFoundEvent extends CacheEvent
{
    public function getLogMessage()
    {
        return sprintf("FOUND: %s", $this->key);
    }
}
