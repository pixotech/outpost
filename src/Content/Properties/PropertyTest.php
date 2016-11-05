<?php

namespace Outpost\Content\Properties;

use Outpost\Content\Variables;
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

    public function testInvokePropertyWithVariable()
    {
        $value = 'test value';
        $variables = new Variables(['varname' => $value]);
        $property = $this->makeProperty('propertyWithVariable');
        $this->assertEquals($value, call_user_func($property, $variables));
        $this->assertEquals(0, self::$callbackCount);
    }

    public function testInvokePropertyWithVariableAndCallback()
    {
        $value = 'test value';
        $variables = new Variables(['varname' => $value]);
        $property = $this->makeProperty('propertyWithVariableAndCallback');
        $this->assertEquals($value, call_user_func($property, $variables));
        $this->assertEquals(1, self::$callbackCount);
    }

    protected function makeProperty($name)
    {
        return new Property(new ReflectionProperty(new \ReflectionProperty(__CLASS__, $name)));
    }
}
