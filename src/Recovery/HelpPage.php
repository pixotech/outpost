<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery;

use Outpost\Exceptions\Exception;
use Outpost\SiteInterface;

class HelpPage
{

    protected $exception;

    public static function forException(\Exception $e)
    {
        return <<<HELP

<p>An exception was thrown:</p>

<pre>{$e->getMessage()}</pre>

<p>The exception was thrown on line {$e->getLine()} of <code>{$e->getFile()}</code>.</p>

HELP;
    }

    public static function getOutpostPath()
    {
        return realpath(__DIR__ . '/..');
    }

    public static function isOutpostPath($path)
    {
        $prefix = self::getOutpostPath() . '/';
        return substr(realpath($path), 0, strlen($prefix)) == $prefix;
    }

    public static function makeExceptionString(\Exception $e)
    {
        return sprintf("%s (%s, line %d)", $e->getMessage(), $e->getFile(), $e->getLine());
    }

    public function __construct(\Exception $exception, SiteInterface $site = null)
    {
        $this->exception = $exception;
        if (isset($site)) $this->site = $site;
    }

    public function __toString()
    {
        try {
            return (string)$this->makePage();
        } catch (\Exception $e) {
            return self::makeExceptionString($e);
        }
    }

    protected function getBody()
    {
        if ($this->exception instanceof Exception) {
            return $this->exception->getHelp();
        }
        return self::forException($this->exception);
    }

    protected function getStylesheet()
    {
        return <<<CSS

body {
    margin: 0;
    padding: 5vh 10vw;
    background-color: #fff;
    color: #060606;
    line-height: 1.35em;
    font-size: 1.35em;
    font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
}
pre {
    background-color: #e6e6e6;
    margin: 1rem -1rem;
    max-width: 100%;
    overflow-x: auto;
    padding: 1rem;
}
address {
    color: #999;
    font-size: .6em;
    font-style: italic;
    margin-top: 4rem;
}

CSS;

    }

    protected function makePage()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ATTENTION</title>
    <style>{$this->getStylesheet()}</style>
    <meta name="viewport" content="width=device-width">
</head>
<body>
    {$this->getBody()}
    <address>This page was generated automatically by your Outpost installation.</address>
</body>
</html>
HTML;
    }

    protected function isOutpostException()
    {
        return self::isOutpostPath($this->exception->getFile());
    }
}