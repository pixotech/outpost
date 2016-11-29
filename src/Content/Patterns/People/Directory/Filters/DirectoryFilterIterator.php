<?php

namespace Outpost\Content\Patterns\People\Directory\Filters;

use Outpost\Content\Patterns\People\Directory\DirectoryInterface;

abstract class DirectoryFilterIterator extends \FilterIterator implements DirectoryFilterIteratorInterface {

  /**
   * @var DirectoryInterface
   */
  protected $directory;

  /**
   * @param DirectoryInterface $directory
   */
  public function __construct(DirectoryInterface $directory) {
    $this->directory = $directory;
    parent::__construct(new \IteratorIterator($directory));
  }

  /**
   * @return DirectoryInterface
   */
  public function getDirectory() {
    return $this->directory;
  }
}
