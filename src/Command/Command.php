<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Command;

use Outpost\PatternLab\HasPatternLabInterface;
use Outpost\PatternLab\PatternLab;
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
      case 'update':
        $this->handleUpdateCommand($input);
        break;
      case null;
        \cli\line("Enter a command");
        break;
      default:
        \cli\line("Unknown command");
    }
  }

  protected function clearAssetMarkers() {
    $dir = $this->getAssetMarkerDirectory();
    if (is_dir($dir)) $this->clearDirectory($dir, 'asset markers');
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
    $dir = $this->getGeneratedAssetsDirectory();
    if (is_dir($dir)) $this->clearDirectory($dir, 'generated assets');
  }

  protected function clearTwigCache() {
    $this->clearDirectory($this->getTwigCacheDirectory(), 'Twig cache');
  }

  protected function ensureDirectory($dir) {
    if (!is_dir($dir)) {
      $this->ensureDirectory(dirname($dir));
      mkdir($dir);
    }
  }

  protected function flushCache() {
    \cli\out("Flushing the primary cache...");
    $this->site->getCache()->getCache()->flush();
    \cli\out("done.\n");
  }

  protected function getAssetMarkerDirectory() {
    return $this->site->getEnvironment()->getAssetCacheDirectory();
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
        $this->clearAssetMarkers();
        break;
      default:
        \cli\line("Clear what?");
    }
  }

  protected function handleUpdateCommand($input) {
    $update = $input[1];
    switch ($update) {
      case 'patternlab':
      case 'pl':
        $this->updatePatternLab();
        break;
      default:
        \cli\line("Update what?");
    }
  }

  protected function updatePatternLab() {
    if (!($this->site instanceof HasPatternLabInterface)) {
      \cli\line("This is not a Pattern Lab site");
      return;
    }
    $publicRoot = $this->site->getPublicDirectory();
    $patternlab = $this->site->get(new PatternLab(__DIR__ . '/../../../../patternlab'));
    foreach ($patternlab->getAssets() as $path => $sourcePath) {
      $destinationPath = $publicRoot . '/' . $path;
      if (is_file($destinationPath)) {
        $isNew = false;
        if (md5_file($sourcePath) == md5_file($destinationPath)) {
          \cli\line($this->makeAssetUpdateMessage($path, 'unchanged'));
          continue;
        }
      }
      else {
        $isNew = true;
        $this->ensureDirectory(dirname($destinationPath));
      }
      $status = copy($sourcePath, $destinationPath) ? ($isNew ? 'created' : 'updated') : 'failed';
      \cli\line($this->makeAssetUpdateMessage($path, $status));
    }
  }

  protected function makeAssetUpdateMessage($path, $status) {
    switch ($status) {
      case 'created':
        $statusStr = "%GCREATED%n";
        break;
      case 'failed':
        $statusStr = "%RFAILED%n";
        break;
      case 'unchanged':
        $statusStr = "%CUNCHANGED%n";
        break;
      case 'updated':
        $statusStr = "%GUPDATED%n";
        break;
    }
    return sprintf("%s - %s", $path, $statusStr);
  }
}