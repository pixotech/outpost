<?php

namespace Outpost\Images;

class IsDimensionsConstraint extends \PHPUnit_Framework_Constraint {

  protected $height;
  protected $width;

  public function __construct($width, $height) {
    parent::__construct();
    $this->width = $width;
    $this->height = $height;
  }

  public function toString() {
    return 'is the correct dimensions';
  }

  public function matches($imagePath) {
    $info = getimagesize($imagePath);
    return $info[0] == $this->width && $info[1] == $this->height;
  }
}