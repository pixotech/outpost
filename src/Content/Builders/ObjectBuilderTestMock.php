<?php

namespace Outpost\Content\Builders;

class ObjectBuilderTestMock
{
    public $publicPropertyWithSetter;

    public $publicPropertyWithoutSetter;

    protected $protectedPropertyWithSetter;

    protected $protectedPropertyWithoutSetter;

    public function setPublicPropertyWithSetter($value)
    {
        $this->publicPropertyWithSetter = $value * 11;
    }

    public function getProtectedPropertyWithSetter()
    {
        return $this->protectedPropertyWithSetter;
    }

    public function setProtectedPropertyWithSetter($value)
    {
        $this->protectedPropertyWithSetter = $value * 11;
    }

    public function getProtectedPropertyWithoutSetter()
    {
        return $this->protectedPropertyWithoutSetter;
    }
}
