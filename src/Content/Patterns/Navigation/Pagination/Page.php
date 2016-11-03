<?php

namespace Outpost\Content\Patterns\Navigation\Pagination;

class Page implements PageInterface
{
    /**
     * @var bool
     */
    protected $current = false;

    /**
     * @var int
     */
    protected $number;

    /**
     * @var string
     */
    protected $url;

    public function __construct($number, $url, $current = false)
    {
        $this->number = $number;
        $this->url = $url;
        $this->current = $current;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function isCurrent()
    {
        return $this->current;
    }
}
