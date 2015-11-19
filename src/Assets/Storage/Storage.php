<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Storage;

use Outpost\Assets\AssetInterface;
use Outpost\Assets\Files\FileInterface;
use Outpost\Assets\Files\ImageFile;
use Outpost\Assets\Images\ImageInterface;

abstract class Storage implements \IteratorAggregate, StorageInterface {

  protected $path;

  public function __construct($path) {
    $this->path = $path;
  }

  public function getFile(AssetInterface $asset) {
    $file = $this->makeFile($asset);
    if (!$file->exists()) $this->generateAssetFile($asset, $file);
    return $file;
  }

  public function getIterator() {
    $flags = \RecursiveIteratorIterator::CHILD_FIRST;
    return new \RecursiveIteratorIterator($this->getDirectoryIterator(), $flags);
  }

  protected function ensureDirectory($dir) {
    if (!is_dir($dir)) {
      $this->ensureDirectory(dirname($dir));
      mkdir($dir);
    }
  }

  protected function ensureFileDirectory(FileInterface $file) {
    $this->ensureDirectory(dirname($file->getPath()));
  }

  protected function generateAssetFile(AssetInterface $asset, FileInterface $file) {
    $this->ensureFileDirectory($file);
    $asset->generate($file, $this);
  }

  protected function getDirectoryIterator() {
    return new \RecursiveDirectoryIterator($this->path);
  }

  protected function makeFile(AssetInterface $asset) {
    if ($asset instanceof ImageInterface) return $this->makeImageFile($asset);
    throw new \InvalidArgumentException("Unrecognized asset type");
  }

  protected function makeImageFile(ImageInterface $image) {
    return new ImageFile($this->makePath($image), $this->makeUrl($image), $image->getAlt());
  }

  protected function makePath(AssetInterface $asset) {
    return $this->path . '/' . $asset->getKey() . '.' . $asset->getExtension();
  }
}