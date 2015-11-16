<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images;

use Outpost\Assets\AssetManagerInterface;
use Outpost\Assets\Images\Overlays\Overlay;
use Outpost\Assets\Images\Overlays\OverlayImage;
use Outpost\SiteInterface;

class ImageWithOverlay extends Image {

  protected $image;
  protected $overlay;

  public function __construct(Image $image, Overlay $overlay) {
    parent::__construct();
    $this->image = $image;
    $this->overlay = $overlay;
  }

  public function generate(\SplFileInfo $file, AssetManagerInterface $assets) {
    $image = $assets->getAssetFile($this->image);
    $overlay = $assets->getAssetFile($this->getOverlayImage($image));
    $command = sprintf("composite -compose %s %s %s %s", $this->getMode(), $image->getPathname(), $overlay->getPathname(), $file->getPathname());
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

  protected function getOverlayImage(\SplFileInfo $imageFile) {
    $info = getimagesize($imageFile->getPathname());
    return new OverlayImage($this->getOverlay(), $info[0], $info[1]);
  }

  protected function getMode() {
    return 'Multiply';
  }
}