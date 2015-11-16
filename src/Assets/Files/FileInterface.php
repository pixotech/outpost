<?php

namespace Outpost\Assets\Files;

interface FileInterface {
  public function getExtension();
  public function getUrl();
}