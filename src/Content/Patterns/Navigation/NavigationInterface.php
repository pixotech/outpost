<?php

namespace Outpost\Content\Patterns\Navigation;

use Outpost\Content\Patterns\Navigation\Menus\MenuInterface;

interface NavigationInterface
{
    public function addMenu($name, MenuInterface $menu);

    public function getBreadcrumbs();

    public function getMenu($name);
}
