<?php

namespace Outpost\Assets;

interface AssetManagerInterface {

  /**
   * @param string $key
   */
  public function clearAssetMarker($key);

  /**
   * @param AssetInterface $asset
   */
  public function createAssetMarker(AssetInterface $asset);

  /**
   * @param AssetInterface $asset
   * @return \SplFileInfo
   */
  public function getAssetFile(AssetInterface $asset);

  /**
   * @param string $key
   * @return AssetInterface
   */
  public function getAssetMarker($key);

  /**
   * @param string $key
   * @return string
   */
  public function getAssetMarkerPath($key);

  /**
   * @param AssetInterface $asset
   * @return string
   */
  public function getAssetUrl(AssetInterface $asset);

  /**
   * @param string $key
   * @return bool
   */
  public function hasAssetMarker($key);

  /**
   * @param AssetInterface $asset
   * @return bool
   */
  public function hasLocalAsset(AssetInterface $asset);

}