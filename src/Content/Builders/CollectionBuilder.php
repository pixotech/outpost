<?php

namespace Outpost\Content\Builders;

class CollectionBuilder extends Builder implements CollectionBuilderInterface
{
    /**
     * @var BuilderInterface
     */
    protected $extraction;

    public function __construct(BuilderInterface $extraction)
    {
        $this->extraction = $extraction;
    }

    public function make(array $data)
    {
        $items = [];
        foreach ($data as $key => $item) {
            if (!is_array($item)) continue;
            $items[$key] = $this->extraction->make($item);
        }
        return $items;
    }
}
