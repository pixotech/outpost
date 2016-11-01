<?php

namespace Outpost\Content;

class ContentClassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @outpost\content\variable varname
     */
    public $propertyWithVariable;

    public $invalidProperty;

    public function testMakeContentClass()
    {
        $contentClass = new ContentClass(__CLASS__);
        $this->assertCount(1, $contentClass->getProperties());
    }

    public function testCreateInstance()
    {
        $value = 'test value';
        $contentClass = new ContentClass(__CLASS__);
        $instance = $contentClass(new Variables(['varname' => $value]));
        $this->assertInstanceOf(__CLASS__, $instance);
        $this->assertEquals($value, $instance->propertyWithVariable);
    }
}
