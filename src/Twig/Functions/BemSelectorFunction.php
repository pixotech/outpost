<?php

namespace Outpost\Twig\Functions;

use Outpost\BEM\Selector;

class BemSelectorFunction extends \Twig_SimpleFunction
{
    public function __construct(array $options = [])
    {
        parent::__construct('bem', [Selector::class, 'make'], $options);
    }
}
