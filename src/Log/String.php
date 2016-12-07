<?php

namespace Outpost\Log;

class String
{
    protected $color;

    protected $content;

    public function __construct($content, Color $color = null)
    {
        $this->content = $content;
        $this->color = $color;
    }

    public function __toString()
    {
        $str = is_array($this->content) ? implode('', $this->content) : (string)$this->content;
        if (!empty($this->color)) {
            $str = $this->color . $str . Color::reset();
        }
        return $str;
    }
}
