<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use Outpost\Assets\StorageInterface;
use Outpost\Assets\FileInterface;
use Outpost\Images\Imagemagick\Geometry\Dimensions;
use Outpost\Images\Imagemagick\Geometry\DimensionsWithOffset;

class ResizedImage extends Image {

  protected $height;
  protected $image;
  protected $width;

  public function __construct(ImageInterface $image, $width, $height) {
    $this->image = $image;
    $this->width = $width;
    $this->height = $height;
  }

  public function generate(FileInterface $file, StorageInterface $storage) {
    $source = $storage->getFile($this->getImage())->getPath();
    $size = $this->getDimensions();
    $crop = $this->getCroppingDimensions();
    $path = $file->getPath();
    $command = "convert $source -resize $size -gravity center -crop $crop +repage $path";
    exec($command);
  }

  public function getAlt() {
    return $this->getImage()->getAlt();
  }

  public function getCroppingDimensions() {
    return new DimensionsWithOffset($this->getWidth(), $this->getHeight());
  }

  public function getDimensions() {
    return new Dimensions($this->getWidth(), $this->getHeight(), '^');
  }

  public function getExtension() {
    return $this->getImage()->getExtension();
  }

  public function getHeight() {
    return $this->height;
  }

  public function getImage() {
    return $this->image;
  }

  public function getKey() {
    return self::makeKey(__CLASS__, $this->getImageKey(), $this->getWidth(), $this->getHeight());
  }

  public function getWidth() {
    return $this->width;
  }

  protected function getImageKey() {
    return $this->getImage()->getKey();
  }
}

