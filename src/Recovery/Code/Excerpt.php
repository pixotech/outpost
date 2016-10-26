<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery\Code;

class Excerpt {

  protected $file;
  protected $line;
  protected $radius;

  public function __construct($file, $line, $radius = 5) {
    $this->file = $file;
    $this->line = $line;
    $this->radius = $radius;
  }

  public function __toString() {
    $firstLine = $this->line - $this->radius;
    $lastLine = $this->line + $this->radius;
    if ($firstLine < 1) {
      $lastLine += -$firstLine;
      $firstLine = 1;
    }
    $fp = fopen($this->file, 'r');
    $lineNumber = 0;
    $excerpt = array();
    while ($line = fgets($fp, 1024)) {
      $lineNumber++;
      if ($lineNumber >= $firstLine) {
        $excerpt[$lineNumber] = new Line($line, $lineNumber, $lineNumber == $this->line);
      }
      if ($lineNumber >= $lastLine) {
        break;
      }
    }
    if (!empty($excerpt)) {
      $str  = '<p class="file">' . $this->file . ', line ' . $this->line . '</p>';
      $str .= '<div class="code">' . implode('', $excerpt) . '</div>';
      return $str;
    }
    return '';
  }

}
