<?php

namespace Outpost\Console\CommandLine\Commands;

use Outpost\SiteInterface;
use Symfony\Component\Console\Command\Command;

class ClearCacheCommand extends Command
{
    protected $outpost;

    public function __construct(SiteInterface $outpost)
    {
        parent::__construct();
        $this->outpost = $outpost;
    }
}