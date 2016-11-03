<?php

namespace Outpost\Content\Patterns\Images;

interface ImageInterface
{
    /**
     * @return string
     */
    public function getAlt();

    /**
     * @return int
     */
    public function getHeight();

    /**
     * @return string
     */
    public function getShape();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return int
     */
    public function getWidth();
}
