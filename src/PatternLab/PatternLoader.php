<?php

namespace Outpost\PatternLab;

use Outpost\PatternLab\PatternLabInterface;

class PatternLoader implements \Twig_LoaderInterface {

  protected $cache = [];
  protected $patternlab;

  public function __construct(PatternLabInterface $patternlab) {
    $this->patternlab = $patternlab;
  }

  public function getSource($name) {
    return file_get_contents($this->getTemplatePath($name));
  }

  public function getCacheKey($name) {
    return md5($this->getTemplatePath($name));
  }

  public function isFresh($name, $time) {
    return filemtime($this->getTemplatePath($name)) > $time;
  }

  protected function getTemplatePath($name) {
    if (!isset($this->cache[$name])) {
      $this->cache[$name] = $this->patternlab->getTemplatePath($name);
    }
    return $this->cache[$name];
  }
}