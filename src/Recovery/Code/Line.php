<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery\Code;

class Line {

  protected $code;
  protected $highlighted;
  protected $number;

  public function __construct($code, $number, $highlighted = false) {
    $this->code = $code;
    $this->number = $number;
    $this->highlighted = $highlighted;
  }

  public function __toString() {
    $html  = '<span class="number">' . $this->number . '</span> ';
    $html .= '<code>' . htmlentities($this->code) . '</code>';
    $lineClass = $this->highlighted ? 'highlighted line' : 'line';
    return "<div class=\"{$lineClass}\">$html</div>";
  }

  public function getCode() {
    return $this->code;
  }

  public function getNumber() {
    return $this->number;
  }
}