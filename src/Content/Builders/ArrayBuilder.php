<?php

namespace Outpost\Content\Builders;

class ArrayBuilder extends Builder implements ArrayBuilderInterface
{
    /**
     * @var BuilderInterface[]
     */
    protected $values = [];

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            $this->values[$key] = Builder::create($value);
        }
    }

    public function make(array $data)
    {
        $values = [];
        foreach ($this->values as $key => $value) {
            $values[$key] = $value->make($data);
        }
        return $values;
    }
}
