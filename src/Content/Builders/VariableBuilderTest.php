<?php

namespace Outpost\Content\Builders;

class VariableBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildVariable()
    {
        $data = ['one' => 1];
        $builder = new VariableBuilder('one');
        $this->assertEquals($data['one'], $builder->make($data));
    }

    public function testCreate()
    {
        $data = ['one' => 1];
        $builder = Builder::create('one');
        $this->assertEquals($data['one'], $builder->make($data));
    }

    public function testUndefinedVariableReturnsNull()
    {
        $data = ['one' => 1];
        $extraction = new VariableBuilder('two');
        $this->assertNull($extraction->make($data));
    }

    public function testBuildNestedVariable()
    {
        $data = ['one' => ['two' => 2]];
        $builder = new VariableBuilder('one/two');
        $this->assertEquals($data['one']['two'], $builder->make($data));
    }
}
