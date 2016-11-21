<?php

namespace Outpost\Content\Builders;

class ObjectBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildObject()
    {
        $data = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
        ];
        $map = [
            'publicPropertyWithSetter' => 'one',
            'publicPropertyWithoutSetter' => 'two',
            'protectedPropertyWithSetter' => 'three',
            'protectedPropertyWithoutSetter' => 'four',
        ];
        $builder = new ObjectBuilder(ObjectBuilderTestMock::class, $map);
        $obj = $builder->make($data);
        $this->assertEquals(11, $obj->publicPropertyWithSetter);
        $this->assertEquals(2, $obj->publicPropertyWithoutSetter);
        $this->assertEquals(33, $obj->getProtectedPropertyWithSetter());
        $this->assertEquals(null, $obj->getProtectedPropertyWithoutSetter());
    }

    public function testCreate()
    {
        $data = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
        ];
        $definition = [
            '$class' => ObjectBuilderTestMock::class,
            'publicPropertyWithSetter' => 'one',
            'publicPropertyWithoutSetter' => 'two',
            'protectedPropertyWithSetter' => 'three',
            'protectedPropertyWithoutSetter' => 'four',
        ];
        $builder = Builder::create($definition);
        $obj = $builder->make($data);
        $this->assertEquals(11, $obj->publicPropertyWithSetter);
        $this->assertEquals(2, $obj->publicPropertyWithoutSetter);
        $this->assertEquals(33, $obj->getProtectedPropertyWithSetter());
        $this->assertEquals(null, $obj->getProtectedPropertyWithoutSetter());
    }
}
