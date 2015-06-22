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
use Outpost\Images\Overlays\Overlay;
use Outpost\Images\Overlays\OverlayImage;

class ImageWithOverlay extends Image {

  protected $image;
  protected $overlay;

  public function __construct(Image $image, Overlay $overlay) {
    $this->image = $image;
    $this->overlay = $overlay;
  }

  public function generate(FileInterface $file, StorageInterface $storage) {
    $image = $storage->getFile($this->getImage());
    $overlay = $storage->getFile($this->getOverlayImage($image));
    $command = sprintf("composite -compose %s %s %s %s", $this->getMode(), $image->getPath(), $overlay->getPath(), $file->getPath());
    exec($command);
  }

  public function getAlt() {
    return $this->getImage()->getAlt();
  }

  public function getExtension() {
    return $this->getImage()->getExtension();
  }

  public function getImage() {
    return $this->image;
  }

  public function getKey() {
    return self::makeKey(__CLASS__, $this->getImage()->getKey(), $this->getOverlay());
  }

  public function getOverlay() {
    return $this->overlay;
  }

  protected function getOverlayImage(FileInterface $imageFile) {
    return new OverlayImage($this->getOverlay(), $imageFile->getWidth(), $imageFile->getHeight());
  }

  protected function getMode() {
    return 'Multiply';
  }
}
