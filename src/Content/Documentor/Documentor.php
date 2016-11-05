<?php

namespace Outpost\Content\Documentor;

use Outpost\SiteInterface;
use Psr\Log\LoggerInterface;

class Documentor implements DocumentorInterface
{
    protected $log;

    protected $twig;

    public static function camelCaseToWords($str) {
        return trim(preg_replace('/([A-Z])/', ' $1', $str));
    }

    public function __construct(SiteInterface $site, LoggerInterface $log = null)
    {
        if (isset($log)) $this->log = $log;
        $this->makeTwigParser();
    }

    public function generate($dest)
    {
        if (!is_dir($dest)) {
            throw new \InvalidArgumentException("Not a directory: $dest");
        }
    }

    protected function makeTwigParser()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../../../templates'));
    }
}
