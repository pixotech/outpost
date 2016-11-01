<?php

namespace Outpost\Content;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    protected static $callbackCount = 0;

    /**
     * @outpost\content\variable varname
     */
    public $propertyWithVariable;

    /**
     * @outpost\content\variable varname
     * @outpost\content\callback Outpost\Content\PropertyTest::callbackMethod
     */
    public $propertyWithVariableAndCallback;

    /**
     * This property doesn't define any content attributes
     */
    public $invalidProperty;

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
        $callback = $property->getCallback();
        $this->assertEquals(['Outpost\Content\PropertyTest', 'callbackMethod'], $callback);
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

    /**
     * @expectedException \DomainException
     */
    public function testInvalidProperty()
    {
        $this->makeProperty('invalidProperty');
    }

    protected function makeProperty($name)
    {
        return new Property(new \ReflectionProperty(__CLASS__, $name));
    }
}
