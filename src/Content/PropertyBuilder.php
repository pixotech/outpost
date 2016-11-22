<?php

namespace Outpost\Content;

use Outpost\Content\Builders\Builder;
use Outpost\Content\Builders\VariableBuilder;

class PropertyBuilder extends Builder
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var string
     */
    protected $variable;

    public function __construct($variable = null, callable $callback = null)
    {
        if (isset($variable)) $this->variable = str_replace('.', '/', $variable);
        if (isset($callback)) $this->callback = $callback;
    }

    public function make(array $data)
    {
        if (!empty($this->variable)) {
            $builder = new VariableBuilder($this->variable);
            $value = $builder->make($data);
        } else {
            $value = $data;
        }
        if (!empty($this->callback)) {
            $value = call_user_func($this->callback, $value);
        }
        return $value;
    }
}
