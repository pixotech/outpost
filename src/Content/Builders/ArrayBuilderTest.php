<?php

namespace Outpost\Content\Builders;

class ArrayBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildArray()
    {
        $data = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
        ];
        $builder = new ArrayBuilder(['a' => 'two', 'b' => 'four']);
        $this->assertEquals(['a' => 2, 'b' => 4], $builder->make($data));
    }

    public function testCreate()
    {
        $data = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
        ];
        $builder = Builder::create(['a' => 'two', 'b' => 'four']);
        $this->assertEquals(['a' => 2, 'b' => 4], $builder->make($data));
    }
}
