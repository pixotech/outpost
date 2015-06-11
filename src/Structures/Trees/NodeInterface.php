<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

interface NodeInterface {
  public function addChildNode(NodeInterface $node);
  public function getId();
  public function getParentId();
}