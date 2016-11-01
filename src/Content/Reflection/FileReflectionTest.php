<?php

namespace Outpost\Content\Reflection;

use Outpost\Site;
use Outpost\Site as AnotherKindOfSite;
use Exception;

class TestClassDefinedInThisFile {}

class FileReflectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFileNamespace()
    {
        $ref = new FileReflection(__FILE__);
        $this->assertEquals(__NAMESPACE__, $ref->getNamespace());
    }

    public function testGetClassesInFile()
    {
        $ref = new FileReflection(__FILE__);
        $classes = [__NAMESPACE__ . '\\' . 'TestClassDefinedInThisFile', __CLASS__];
        $this->assertEquals($classes, $ref->getClasses());
    }

    public function testGetAliasesInFile()
    {
        $ref = new FileReflection(__FILE__);
        $aliases = [
            'Site' => 'Outpost\Site',
            'AnotherKindOfSite' => 'Outpost\Site',
            'Exception' => 'Exception',
        ];
        $this->assertEquals($aliases, $ref->getAliases());
    }
}

