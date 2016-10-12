<?php

namespace Outpost\Routing;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
    /**
     * @param Request $request
     * @return callable
     */
    public function getResponder(Request $request);
}
