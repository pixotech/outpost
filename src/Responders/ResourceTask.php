<?php

namespace Outpost\Responders;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceTask extends Task
{
    protected $resource;

    public function __construct(callable $resource)
    {
        $this->resource = $resource;
    }

    public function __invoke(SiteInterface $site, Request $request, $context = null)
    {
        try {
            return $site->get($this->resource);
        } catch (\Exception $e) {
            throw new ResourceException($this->resource);
        }
    }
}