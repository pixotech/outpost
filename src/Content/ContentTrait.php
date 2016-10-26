<?php

namespace Outpost\Content;

trait ContentTrait
{
    public static function make($variables)
    {
        if (!is_array($variables)) return null;
        return call_user_func(new ContentClass(get_called_class()), new Variables($variables));
    }

    public static function makeAll(array $items)
    {
        return array_map([get_called_class(), 'make'], $items);
    }
}
