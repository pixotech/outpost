<?php

namespace Outpost\Responders;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

interface TaskInterface
{
    public function __invoke(SiteInterface $site, Request $request, $context = null);
}