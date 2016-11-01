<?php

namespace Outpost\Content\Reflection;

interface FileReflectionInterface
{
    public function getAlias($name);

    public function getAliases();

    public function getClasses();

    public function getNamespace();

    public function hasAlias($name);
}
