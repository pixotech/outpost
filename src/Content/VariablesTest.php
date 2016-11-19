<?php

namespace Outpost\Content;

class VariablesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVariable()
    {
        $name = 'one';
        $value = 1;
        $vars = new Variables([$name => $value]);
        $this->assertEquals($value, $vars->get($name));
    }

    public function testGetNestedVariable()
    {
        $value = 2;
        $vars = new Variables(['one' => ['two' => $value]]);
        $this->assertEquals($value, $vars->get('one/two'));
    }

    public function testGetNestedVariableWithDots()
    {
        $value = 2;
        $vars = new Variables(['one' => ['two' => $value]]);
        $this->assertEquals($value, $vars->get('one.two'));
    }

    public function testCantGetNestedVariableOfNonArray()
    {
        $vars = new Variables(['one' => 1]);
        $this->assertNull($vars->get('one/two'));
    }

    public function testIsReference()
    {
        $this->assertTrue(Variables::isReference(['$ref' => 'file.json']));
    }

    public function testNotReferenceBecauseTooManyValues()
    {
        $this->assertFalse(Variables::isReference(['$ref' => 'file.json', 'one' => 'two']));
    }

    public function testNotReferenceBecauseWrongKey()
    {
        $this->assertFalse(Variables::isReference(['ref' => 'file.json']));
    }

    public function testReference()
    {
        $ref = "another/file.json";
        $vars = new Variables(['one' => ['$ref' => $ref]]);
        $this->assertInstanceOf(Reference::class, $vars->get('one'));
    }
}
