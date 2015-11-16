<?php

namespace Outpost\Assets;

use Outpost\Events\ExceptionEvent;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetManager implements AssetManagerInterface {


  protected $baseUrl;

  protected $cacheDirectory;

  protected $generatedAssetsDirectory;

  public function __construct($baseUrl, $generatedAssetsDirectory, $cacheDirectory) {
    $this->baseUrl = $baseUrl;
    $this->generatedAssetsDirectory = $generatedAssetsDirectory;
    $this->cacheDirectory = $cacheDirectory;
  }

  /**
   * Clear an asset marker
   *
   * @param string $key
   */
  public function clearAssetMarker($key) {
    unlink($this->getAssetMarkerPath($key));
  }

  /**
   * Create an asset marker
   *
   * @param AssetInterface $asset
   */
  public function createAssetMarker(AssetInterface $asset) {
    $key = $asset->getKey();
    file_put_contents($this->getAssetMarkerPath($key), serialize($asset));
  }

  /**
   * @param AssetInterface $asset
   * @throws \Exception
   */
  public function generateAsset(AssetInterface $asset) {
    $file = new \SplFileInfo($this->getLocalAssetPath($asset));
    $asset->generate($file, $this);
    if (!$file->isFile()) throw new \Exception("Could not create asset: " . $asset->getKey());
  }

  public function getAsset(AssetInterface $asset) {
    if (!$this->hasLocalAsset($asset)) $this->createAssetMarker($asset);
    return new LocalAsset($asset, $this->getAssetUrl($asset));
  }

  /**
   * @return string
   */
  public function getAssetBaseUrl() {
    return $this->baseUrl;
  }

  /**
   * @return string
   */
  public function getAssetCacheDirectory() {
    return $this->cacheDirectory;
  }

  /**
   * Get a local asset file
   *
   * @param AssetInterface $asset
   * @return \SplFileInfo
   */
  public function getAssetFile(AssetInterface $asset) {
    if (!$this->hasLocalAsset($asset)) $this->generateAsset($asset);
    return new \SplFileInfo($this->getLocalAssetPath($asset));
  }

  /**
   * Get an asset marker
   *
   * @param string $key
   * @return AssetInterface
   * @throws \OutOfBoundsException
   */
  public function getAssetMarker($key) {
    $marker = $this->getAssetMarkerPath($key);
    if (!file_exists($marker)) throw new \OutOfBoundsException("Unknown asset: $key");
    return unserialize(file_get_contents($marker));
  }

  /**
   * Get the path to an asset marker
   *
   * @param string $key
   * @return string
   */
  public function getAssetMarkerPath($key) {
    return $this->getAssetCacheDirectory() . '/' . $key;
  }

  /**
   * @return string
   */
  public function getAssetPathRegex() {
    $base = preg_quote($this->getAssetBaseUrl());
    return "|^{$base}([a-f0-9]{32})\.(.+)$|";
  }

  /**
   * Get an asset's URL
   *
   * @param AssetInterface $asset
   * @return string
   */
  public function getAssetUrl(AssetInterface $asset) {
    return $this->getAssetBaseUrl() . $asset->getKey() . '.' . $asset->getExtension();
  }

  /**
   * @return string
   */
  public function getGeneratedAssetsDirectory() {
    return $this->generatedAssetsDirectory;
  }

  /**
   * @param AssetInterface $asset
   * @return string
   */
  public function getLocalAssetPath(AssetInterface $asset) {
    return $this->getGeneratedAssetsDirectory() . '/' . $asset->getKey() . '.' . $asset->getExtension();
  }

  /**
   * @param string $key
   * @return \SplFileInfo
   */
  public function getRequestedAssetFile($key) {
    $asset = $this->getAssetMarker($key);
    #$this->clearAssetMarker($key);
    $file = $this->getAssetFile($asset);
    return $file;
  }

  /**
   * @param Request $request
   * @return null
   */
  public function getRequestedAssetKey(Request $request) {
    return preg_match($this->getAssetPathRegex(), $request->getPathInfo(), $m) ? $m[1] : null;
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasAssetMarker($key) {
    return file_exists($this->getAssetMarkerPath($key));
  }

  /**
   * @param AssetInterface $asset
   * @return bool
   */
  public function hasLocalAsset(AssetInterface $asset) {
    return file_exists($this->getLocalAssetPath($asset));
  }

  /**
   * @param Request $request
   * @return int
   */
  public function isAssetRequest(Request $request) {
    return preg_match($this->getAssetPathRegex(), $request->getPathInfo());
  }
}