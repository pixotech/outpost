<?php

namespace Outpost\Console\CommandLine;

use Outpost\Console\CommandLine\Commands\ClearCacheCommand;
use Outpost\SiteInterface;

class Application extends \Symfony\Component\Console\Application
{
    protected $outpost;

    public function __construct(SiteInterface $outpost)
    {
        parent::__construct();
        $this->outpost = $outpost;
        $this->add(new ClearCacheCommand($outpost));
    }
}