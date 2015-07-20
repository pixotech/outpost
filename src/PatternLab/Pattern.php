<?php

namespace Outpost\PatternLab;

class Pattern implements PatternInterface {

  protected $name;
  protected $templatePath;
  protected $path;
  protected $pathWithoutExtension;
  protected $subtype;
  protected $type;

  /**
   * Break a relative pattern path into segments and remove ordering
   *
   * @param string $path The relative path of a pattern template
   * @return array
   */
  public static function explodePath($path) {
    return array_map([__CLASS__, 'removeOrderingFromPathSegment'], explode('/', $path));
  }

  /**
   * Removes ordering from the beginning of a pattern path segment
   *
   * @param string $segment
   * @return string
   */
  public static function removeOrderingFromPathSegment($segment) {
    return preg_match('/^[0-9]+-(.+)$/', $segment, $m) ? $m[1] : $segment;
  }

  /**
   * @param string $templatePath The full path to the template
   * @param string $path The relative path of the template within the patterns directory
   */
  public function __construct($templatePath, $path) {
    $this->templatePath = $templatePath;
    $this->path = $path;
    $this->pathWithoutExtension = substr($path, 0, -5);
    $parts = self::explodePath($this->pathWithoutExtension);
    $this->type = array_shift($parts);
    $this->name = array_pop($parts);
    $this->subtype = array_shift($parts);
  }

  public function getShorthand() {
    return $this->type . '-' . $this->name;
  }

  public function getTemplatePath() {
    return $this->templatePath;
  }

  public function matches($name) {
    if ($name == $this->getShorthand()) return true;
    if ($name == $this->path) return true;
    if ($name == $this->pathWithoutExtension) return true;
    return false;
  }
}