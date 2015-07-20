<?php

namespace Outpost\PatternLab;

interface PatternLabInterface {
  public function getSourcesPath();
  public function getTemplatePath($name);
}