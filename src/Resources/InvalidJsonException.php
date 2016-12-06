<?php

namespace Outpost\Resources;

class InvalidJsonException extends \DomainException
{
    protected $json;

    public function __construct($json)
    {
        parent::__construct("Invalid JSON");
        $this->json = $json;
    }

    public function getJson()
    {
        return $this->json;
    }
}
