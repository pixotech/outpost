<?php

namespace Outpost\Content\Patterns\Locations;

interface PlaceInterface extends LocationInterface
{
    /**
     * @return AddressInterface
     */
    public function getAddress();

    /**
     * @return string
     */
    public function getName();
}
