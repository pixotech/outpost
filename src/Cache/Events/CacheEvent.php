<?php

namespace Outpost\Cache\Events;

use Outpost\Events\Event;

abstract class CacheEvent extends Event
{
    protected $key;

    public function __construct($key)
    {
        parent::__construct();
        $this->key = $key;
    }

    public function getLocation()
    {
        return 'Cache';
    }
}
