<?php

namespace Outpost\Assets\Files;

interface LocalFileInterface extends FileInterface {
  public function exists();
  public function getPath();
}
