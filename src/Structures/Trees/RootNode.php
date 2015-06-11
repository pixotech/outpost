<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

class RootNode implements \ArrayAccess, \Countable, \RecursiveIterator {

  use HasChildren;

  public function __construct(array $nodes=[]) {
    $nodesById = array();
    $unsorted = $nodes;
    do {
      $toSort = $unsorted;
      $unsorted = [];
      $numberOfItemsSorted = 0;
      foreach ($toSort as $node) {
        if (!($node instanceof NodeInterface)) {
          throw new \UnexpectedValueException();
        }
        $nodesById[$node->getId()] = $node;
        if (!$node->getParentId()) {
          $this->children[] = $nodesById[$node->getId()];
          $numberOfItemsSorted++;
        }
        elseif (isset($nodesById[$node->getParentId()])) {
          $nodesById[$node->getParentId()]->addChildNode($nodesById[$node->getId()]);
          $numberOfItemsSorted++;
        }
        else {
          $unsorted[] = $node;
        }
      }
    }
    while (!empty($unsorted) && $numberOfItemsSorted);
  }
}