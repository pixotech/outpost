<?php

namespace Outpost\Content\Builders;

class VariableBuilder extends Builder implements VariableBuilderInterface
{
    const DELIMITER = '/';

    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function make(array $data)
    {
        return $this->extractVariable($this->name, $data);
    }

    protected function extractVariable($name, array $data)
    {
        $sub = null;
        if ($this->isCompoundName($name)) {
            list ($name, $sub) = $this->splitName($name, 2);
        }
        if (isset($data[$name])) {
            $value = $data[$name];
        } else {
            $value = null;
        }
        if (!empty($sub)) {
            if (is_array($value)) {
                $value = $this->extractVariable($sub, $value);
            } else {
                $value = null;
            }
        }
        return $value;
    }

    protected function isCompoundName($name)
    {
        return false !== strpos($name, self::DELIMITER);
    }

    protected function splitName($name, $limit = null)
    {
        return array_pad(explode(self::DELIMITER, $name, $limit), 2, null);
    }
}
