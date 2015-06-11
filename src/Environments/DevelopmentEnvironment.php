<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Monolog\Handler\StreamHandler;
use Outpost\Log\LogFormatter;
use Stash\Driver\FileSystem;

class DevelopmentEnvironment extends Environment {

  protected function configureErrorReporting() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
  }

  public function getCacheDriver() {
    return $this->makeCacheDriver();
  }

  public function getLogHandlers() {
    return [$this->makeLogHandler()];
  }

  protected function getCachePath() {
    $cachePath = $this->rootDirectory . '/cache/outpost';
    $this->ensureDirectory($cachePath);
    return $cachePath;
  }

  protected function makeCacheDriver() {
    $driver = new FileSystem();
    $driver->setOptions(['dirSplit' => 2, 'path' => $this->getCachePath()]);
    return $driver;
  }

  protected function makeLogHandler() {
    $logPath = $this->rootDirectory . '/log/outpost.log';
    $this->ensureDirectory(dirname($logPath));
    $handler = new StreamHandler($logPath);
    $handler->setFormatter(new LogFormatter());
    return $handler;
  }
}