<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

abstract class Node implements \ArrayAccess, \Countable, \RecursiveIterator, NodeInterface {
  use HasParent, HasChildren;
}