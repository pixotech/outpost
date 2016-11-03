<?php

namespace Outpost\Content\Patterns\Images;

class Image implements ImageInterface
{
    const SHAPE_SQUARE = 'square';

    const SHAPE_TALL = 'tall';

    const SHAPE_WIDE = 'wide';

    /**
     * @var string
     */
    protected $alt;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $width;

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function getShape()
    {
        $w = $this->getWidth();
        $h = $this->getHeight();
        switch (true) {
            case $w == $h:
                return self::SHAPE_SQUARE;
            case $w < $h:
                return self::SHAPE_TALL;
            case $w > $h:
            default:
                return self::SHAPE_WIDE;
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
