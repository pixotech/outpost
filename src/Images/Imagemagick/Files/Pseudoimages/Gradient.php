<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Imagemagick\Files\Pseudoimages;

use Outpost\Images\Imagemagick\Files\InputInterface;

class Gradient implements InputInterface {

  protected $bottomColor;
  protected $topColor;

  public function __construct($topColor, $bottomColor) {
    $this->topColor = $topColor;
    $this->bottomColor = $bottomColor;
  }

  public function __toString() {
    return $this->toString();
  }

  public function toString() {
    return sprintf("gradient:'#%s'-'#%s'", $this->topColor, $this->bottomColor);
  }
}