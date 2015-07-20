<?php

namespace Outpost\Navigation;

interface ItemInterface extends MenuInterface {
  public function getId();
  public function getLabel();
  public function getParent();
  public function getParentId();
  public function getPosition();
  public function getUrl();
  public function hasParent();
  public function setAsActive();
  public function setParent(ItemInterface $item);
}