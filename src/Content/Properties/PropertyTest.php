<?php

namespace Outpost\Content\Properties;

use PHPUnit_Framework_TestCase;
use Outpost\Reflection\Property as ReflectionProperty;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    protected static $callbackCount = 0;

    /**
     * @outpost\content\variable varname
     */
    public $propertyWithVariable;

    /**
     * @outpost\content\variable varname
     * @outpost\content\callback Outpost\Content\Properties\PropertyTest::callbackMethod
     */
    public $propertyWithVariableAndCallback;

    public static function callbackMethod($var)
    {
        self::$callbackCount++;
        return $var;
    }

    protected function setUp()
    {
        parent::setUp();
        self::$callbackCount = 0;
    }

    public function testVariable()
    {
        $property = $this->makeProperty('propertyWithVariable');
        $this->assertEquals('varname', $property->getVariable());
    }

    public function testCallback()
    {
        $property = $this->makeProperty('propertyWithVariableAndCallback');
        $this->assertEquals(['Outpost\Content\Properties\PropertyTest', 'callbackMethod'], $property->getCallback());
    }

    protected function makeProperty($name)
    {
        return new Property(new ReflectionProperty(new \ReflectionProperty(__CLASS__, $name)));
    }
}
