<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Command;

use Outpost\SiteInterface;

class Command {

  protected $simulate = false;
  protected $site;

  public static function make(SiteInterface $site) {
    return new Command($site);
  }

  public function __construct(SiteInterface $site) {
    $this->site = $site;
    \cli\Colors::enable();
  }

  public function __invoke() {
    $this->run();
  }

  public function run() {
    $input = $this->getInput();
    switch ($input[0]) {
      case 'clear':
        $this->handleClearCommand($input);
        break;
      case null;
        \cli\line("Enter a command");
        break;
      default:
        \cli\line("Unknown command");
    }
  }

  protected function clearDirectory($path, $description = null) {
    if (!isset($description)) $description = $path;
    \cli\out("Clearing {$description}...");
    $dir = $this->getTwigCacheDirectory();
    if (is_dir($dir)) {
      $deleter = new Deleter($path);
      $deleter->deleteContents();
      \cli\out("done.\n");
    }
    else {
      \cli\out("not found.\n");
    }
  }

  protected function clearGeneratedAssets() {
    $this->clearDirectory($this->getGeneratedAssetsDirectory(), 'generated assets');
  }

  protected function clearTwigCache() {
    $this->clearDirectory($this->getTwigCacheDirectory(), 'Twig cache');
  }

  protected function flushCache() {
    \cli\out("Flushing the primary cache...");
    $this->site->getCache()->flush();
    \cli\out("done.\n");
  }

  protected function getGeneratedAssetsDirectory() {
    return $this->site->getEnvironment()->getRootDirectory() . '/docroot/_assets';
  }

  protected function getInput() {
    $input = new \Commando\Command();
    return $input;
  }

  protected function getTwigCacheDirectory() {
    return $this->site->getEnvironment()->getRootDirectory() . '/cache/twig';
  }

  protected function handleClearCommand($input) {
    $clear = $input[1];
    switch ($clear) {
      case 'all':
      case 'cache':
        $this->flushCache();
        if ($clear != 'all') break;
      case 'twig':
        $this->clearTwigCache();
        if ($clear != 'all') break;
      case 'assets':
        $this->clearGeneratedAssets();
        break;
      default:
        \cli\line("Clear what?");
    }
  }
}