<?php

namespace Outpost\PatternLab;

use Exception;

class PatternNotFoundException extends \Exception {

  protected $patternLab;
  protected $patternName;

  public function __construct($patternName, PatternLabInterface $patternLab) {
    parent::__construct("Pattern not found: $patternName");
    $this->patternName = $patternName;
    $this->patternLab = $patternLab;
  }

  /**
   * @return \Outpost\PatternLab\PatternLabInterface
   */
  public function getPatternLab() {
    return $this->patternLab;
  }

  /**
   * @return string
   */
  public function getPatternName() {
    return $this->patternName;
  }
}