<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images;

use Outpost\Assets\AssetManagerInterface;
use Outpost\Assets\Files\RemoteFile;

class RemoteImage extends Image {

  protected $url;

  public function __construct($url, $alt = '') {
    parent::__construct($alt);
    $this->url = $url;
  }

  public function getExtension() {
    return pathinfo($this->url, PATHINFO_EXTENSION);
  }

  public function getKey() {
    return self::makeKey(__CLASS__, $this->url);
  }

  public function generate(\SplFileInfo $file, AssetManagerInterface $assets) {
    $source = new RemoteFile($this->url);
    $source->put($file->getPathname());
  }
}