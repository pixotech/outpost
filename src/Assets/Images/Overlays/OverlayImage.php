<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Overlays;

use Outpost\Assets\AssetManagerInterface;
use Outpost\Assets\StorageInterface;
use Outpost\Assets\Files\FileInterface;
use Outpost\Assets\Images\Image;
use Outpost\SiteInterface;

class OverlayImage extends Image {

  protected $height;
  protected $overlay;
  protected $width;

  public function __construct(OverlayInterface $overlay, $width, $height) {
    parent::__construct();
    $this->overlay = $overlay;
    $this->width = $width;
    $this->height = $height;
  }

  public function generate(\SplFileInfo $file, AssetManagerInterface $assets) {
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