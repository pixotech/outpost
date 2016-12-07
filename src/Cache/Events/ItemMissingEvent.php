<?php

namespace Outpost\Cache\Events;

class ItemMissingEvent extends CacheEvent
{
    public function getLogMessage()
    {
        return sprintf("NOT FOUND: %s", $this->key);
    }
}
