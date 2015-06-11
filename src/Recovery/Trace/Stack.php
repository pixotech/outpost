<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery\Trace;

use SplStack, Exception;

class Stack extends SplStack {

  protected $fileRoot;

  public static function getFileRootFromException(Exception $exception) {
    $files = array();
    foreach ($exception->getTrace() as $frame) {
      $files[] = $frame['file'];
    }
    $root = NULL;
    $slash = DIRECTORY_SEPARATOR;
    foreach (explode($slash, $exception->getFile()) as $segment) {
      $path = isset($root) ? $root.$segment.$slash : $segment.$slash;
      $foundMismatch = FALSE;
      foreach ($files as $file) {
        if (substr($file, 0, strlen($path)) != $path) {
          $foundMismatch = TRUE;
          break;
        }
      }
      if ($foundMismatch) {
        break;
      }
      $root = $path;
    }
    return $root;
  }

  public function __construct(Exception $exception) {
    $this->fileRoot = self::getFileRootFromException($exception);
    foreach ($exception->getTrace() as $frame) {
      $this->push(new Frame($this, $frame));
    }
    $this->push($this, []);
  }

  public function __toString() {
    return '<ol class="trace">' . implode('', array_reverse(iterator_to_array($this))) . '</ol>';
  }

  public function getFileRoot() {
    return $this->fileRoot;
  }

  public function makeRelativePath($path) {
    return substr($path, strlen($this->fileRoot));
  }
}