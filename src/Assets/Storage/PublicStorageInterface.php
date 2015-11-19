<?php

namespace Outpost\Assets\Storage;

interface PublicStorageInterface {

  /**
   * @return string
   */
  public function getUrl();

  /**
   * @param string $url
   */
  public function setUrl($url);
}