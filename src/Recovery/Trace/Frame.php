<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery\Trace;

use Outpost\Recovery\Code\Excerpt;
use Outpost\Recovery\HelpPage;

class Frame
{

    protected $function;
    protected $line;
    protected $file;
    protected $class;
    protected $object;
    protected $type;
    protected $arguments;
    protected $reflection;
    protected $stack;

    protected static function reduceArguments(array $args)
    {
        foreach ($args as $i => $arg) {
            if (is_object($arg)) {
                $args[$i] = get_class($arg);
            } elseif (is_array($arg)) {
                $args[$i] = self::reduceArguments($arg);
            }
        }
        return $args;
    }

    public function __construct(Stack $stack, array $frame)
    {
        $this->stack = $stack;
        $this->function = $frame['function'];
        $this->line = $frame['line'];
        $this->file = $frame['file'];
        $this->class = isset($frame['class']) ? $frame['class'] : NULL;
        $this->object = isset($frame['object']) ? $frame['object'] : NULL;
        $this->type = isset($frame['type']) ? $frame['type'] : NULL;
        $this->arguments = $frame['args'];
        $this->reflection = $this->getReflection();
    }

    public function __toString()
    {
        try {
            $trace = '<li>';
            $trace .= $this->makeFunctionString();
            $trace .= $this->makeArgumentsList();
            $trace .= new Excerpt($this->getFile(), $this->getLine());
            $trace .= '</li>';
            return $trace;
        } catch (\Exception $e) {
            return HelpPage::makeExceptionString($e);
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function isFunctionCall()
    {
        return !$this->getType();
    }

    public function isInstanceMethodCall()
    {
        return $this->getType() == '->';
    }

    public function isMethodCall()
    {
        return $this->isInstanceMethodCall() || $this->isStaticMethodCall();
    }

    public function isStaticMethodCall()
    {
        return $this->getType() == '::';
    }

    public function isOutpost()
    {
        return HelpPage::isOutpostPath($this->getFile());
    }

    /**
     * @return \ReflectionFunctionAbstract
     */
    protected function getReflection()
    {
        if (!empty($this->class)) return new \ReflectionMethod($this->class, $this->function);
        else return function_exists($this->function) ? new \ReflectionFunction($this->function) : null;
    }

    protected function makeArgumentsList()
    {
        $table = '';
        if ($this->reflection) {
            $parameters = $this->reflection->getParameters();
            $table .= '<table class="arguments">';
            for ($i = 0, $len = $this->getNumberOfArguments(); $i < $len; $i++) {
                $table .= '<tr>';
                $table .= '<th>';
                if (!empty($parameters[$i])) {
                    $table .= '$' . $parameters[$i]->getName();
                } else {
                    $table .= '...';
                }
                $table .= '</th>';
                $table .= '<td>';
                if (array_key_exists($i, $this->arguments)) {
                    $table .= $this->formatParameterValue($this->arguments[$i]);
                }
                $table .= '</td>';
                $table .= '</tr>';
            }
            $table .= '</table>';
        }
        return $table;
    }

    protected function getNumberOfArguments()
    {
        return max(count($this->arguments), $this->reflection->getNumberOfParameters());
    }

    protected function makeFunctionString()
    {
        $str = '';
        if ($this->reflection) {
            $str .= '<div class="function">';
            if ($this->reflection instanceof \ReflectionMethod) {
                $str .= '<span>' . $this->reflection->getDeclaringClass()->getName() . '</span>';
                $str .= '<span class="separator">::</span>';
            }
            $str .= '<span class="name">' . $this->reflection->getName() . '</span>';
            $str .= $this->formatFunctionParameters();
            $str .= '</div>';


            if ($this->reflection->getDocComment()) {
                $str .= '<pre class="comment">';
                $str .= htmlentities($this->reflection->getDocComment());
                $str .= '</pre>';
            }
        }
        return $str;
    }

    protected function formatFunctionParameters()
    {
        $params = [];
        foreach ($this->reflection->getParameters() as $param) {
            $params[] = $this->makeFunctionParameterString($param);
        }
        return '(' . implode(', ', $params) . ')';
    }

    protected function makeFunctionParameterString(\ReflectionParameter $param)
    {
        $str = '$' . $param->getName();
        if ($param->getClass()) {
            $str = '<span class="hint" title="' . htmlentities($param->getClass()->getName()) . '">' . $param->getClass()->getShortName() . '</span> ' . $str;
        }
        if ($param->isOptional() && !$this->getReflection()->isInternal()) {
            $str .= ' = ';
            $str .= $this->formatParameterValue($param->getDefaultValue());
        }
        return $str;
    }

    protected function formatParameterValue($value)
    {
        if (is_object($value)) {
            return $this->formatObjectParameterValue($value);
        }
        if (is_array($value)) {
            $count = count($value);
            return "array ($count)";
        }
        if (is_string($value)) {
            return '<kbd>' . htmlentities($value) . '</kbd>';
        }
        if (is_bool($value)) {
            return '<i>' . ($value ? 'true' : 'false') . '</i>';
        }
        if (is_null($value)) {
            return '<i>null</i>';
        }
        return htmlentities($value);
    }

    protected function formatObjectParameterValue($value)
    {
        $obj = new \ReflectionObject($value);
        $str = '<div class="object">';
        $str .= $this->formatClassName($obj->getShortName(), $obj->getName());
        $str .= '</div>';
        return $str;
    }

    protected function formatClassName($shortName, $fullName)
    {
        return '<span class="className" title="' . htmlentities($fullName) . '">' . $shortName . '</span>';
    }
}
