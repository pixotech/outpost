<?php

namespace Outpost\Content;

trait ContentTrait
{
    public static function make(array $variables)
    {
        return call_user_func(new ContentClass(get_called_class()), new Variables($variables));
    }
}
