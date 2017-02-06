<?php

namespace Outpost\Responders;

interface ResponderInterface
{
    public function get(callable $resource);

    public function render($template);
}