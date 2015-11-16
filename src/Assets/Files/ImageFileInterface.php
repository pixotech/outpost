<?php

namespace Outpost\Assets\Files;

interface ImageFileInterface extends LocalFileInterface {
  public function getHeight();
  public function getMimeType();
  public function getWidth();
}
