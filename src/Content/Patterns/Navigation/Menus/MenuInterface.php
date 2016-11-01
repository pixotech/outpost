<?php

namespace Outpost\Content\Patterns\Navigation\Menus;

interface MenuInterface
{
    /**
     * @param ItemInterface $item
     */
    public function add(ItemInterface $item);

    public function clearActiveUrl();

    /**
     * @return int
     */
    public function count();

    public function getBreadcrumbs();

    public function getId();

    /**
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return bool
     */
    public function hasActiveItem();

    /**
     * @return bool
     */
    public function hasLabel();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @param string $url
     */
    public function setActiveUrl($url);

    /**
     * @param string $label
     */
    public function setLabel($label);
}
