<?php

namespace Outpost\Log;

class Color
{
    const BLACK = 0;
    const RED = 1;
    const GREEN = 2;
    const YELLOW = 3;
    const BLUE = 4;
    const MAGENTA = 5;
    const CYAN = 6;
    const WHITE = 7;

    protected $background;

    protected $bold = false;

    protected $foreground;

    public static function reset()
    {
        return "\033[0m";
    }

    public function __construct($fg, $bold = false, $bg = null)
    {
        $this->foreground = $fg;
        $this->bold = (bool)$bold;
        $this->background = $bg;
    }

    public function __toString()
    {
        $str = '';
        if (!empty($this->foreground)) {
            $str .= $this->getForegroundColorCode($this->foreground, $this->bold);
        }
        if (!empty($this->background)) {
            $str .= $this->getBackgroundColorCode($this->background);
        }
        return $str;
    }

    protected function getForegroundColorCode($color, $bold = false)
    {
        $bold = $bold ? 1 : 0;
        return $this->getColorCode("{$bold};3{$color}");
    }

    protected function getBackgroundColorCode($color)
    {
        return $this->getColorCode("4{$color}");
    }

    protected function getColorCode($color)
    {
        return "\033[" . $color . "m";
    }
}
