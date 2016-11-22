<?php

namespace Outpost\Content;

trait ContentTrait
{
    public static function make($variables)
    {
        if (!is_array($variables)) return null;
        $builder = new ContentBuilder(get_called_class());
        return $builder->make($variables);
    }

    public static function makeAll($items)
    {
        if (!is_array($items)) return [];
        return array_map([get_called_class(), 'make'], $items);
    }
}
