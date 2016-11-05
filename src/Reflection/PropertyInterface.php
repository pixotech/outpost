<?php

namespace Outpost\Reflection;

interface PropertyInterface
{
    /**
     * @return string
     */
    public function getCallback();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return |ReflectionProperty
     */
    public function getReflection();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getVariable();
}
