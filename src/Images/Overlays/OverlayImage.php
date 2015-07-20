<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Overlays;

use Outpost\Assets\StorageInterface;
use Outpost\Assets\FileInterface;
use Outpost\Images\Image;
use Outpost\SiteInterface;

class OverlayImage extends Image {

  protected $height;
  protected $overlay;
  protected $width;

  public function __construct(OverlayInterface $overlay, $width, $height) {
    $this->overlay = $overlay;
    $this->width = $width;
    $this->height = $height;
  }

  public function generate(SiteInterface $site, \SplFileInfo $file) {
    $this->getOverlay()->generate($file, $this->width, $this->height);
  }

  public function getExtension() {
    return $this->getOverlay()->getExtension();
  }

  /**
   * @return string
   */
  public function getKey() {
    return $this->getOverlay()->getKey($this->width, $this->height);
  }

  /**
   * @return OverlayInterface
   */
  public function getOverlay() {
    return $this->overlay;
  }
}