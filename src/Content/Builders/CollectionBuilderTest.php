<?php

namespace Outpost\Content\Builders;

class CollectionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildCollection()
    {
        $data = [
            ['var' => 1],
            ['var' => 2],
            ['var' => 3],
        ];
        $builder = new CollectionBuilder(new VariableBuilder('var'));
        $this->assertEquals([1, 2, 3], $builder->make($data));
    }

    public function testCreate()
    {
        $data = [
            ['var' => 1],
            ['var' => 2],
            ['var' => 3],
        ];
        $builder = Builder::create('var[]');
        $this->assertEquals([1, 2, 3], $builder->make($data));
    }
}
