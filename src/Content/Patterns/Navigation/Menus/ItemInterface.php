<?php

namespace Outpost\Content\Patterns\Navigation\Menus;

interface ItemInterface
{
    public function clearActive();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return MenuInterface
     */
    public function getMenu();

    /**
     * @return ItemInterface
     */
    public function getParent();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return bool
     */
    public function hasActiveItem();

    /**
     * @return bool
     */
    public function hasParent();

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @return bool
     */
    public function isExternal();

    /**
     * @param bool $active
     */
    public function setActive($active = true);

    /**
     * @param ItemInterface $item
     */
    public function setParent(ItemInterface $item);
}
