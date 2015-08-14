<?php

namespace Outpost\PatternLab;

use Outpost\Cache\CacheableInterface;
use Outpost\PatternLab\PatternNotFoundException;
use Outpost\ResourceInterface;
use Outpost\SiteInterface;

class PatternLab implements PatternLabInterface, CacheableInterface, ResourceInterface {

  protected $assets;
  protected $path;
  protected $patterns;

  public function __construct($path) {
    if (!is_dir($path)) throw new \InvalidArgumentException("Not a directory: $path");
    $this->path = realpath($path);
  }

  public function __invoke(SiteInterface $site) {
    $this->findPatterns();
    return $this;
  }

  public function getAssets() {
    if (!isset($this->assets)) $this->findAssets();
    return $this->assets;
  }

  public function getCacheKey() {
    return "patternlab/" . md5(realpath($this->path));
  }

  public function getCacheLifetime() {
    return 3600; # 1 hour
  }

  public function getFile($path) {
    return new \SplFileInfo($this->makePath($path));
  }

  public function getPattern($name) {
    foreach($this->getPatterns() as $pattern) {
      if ($pattern->matches($name)) return $pattern;
    }
    throw new PatternNotFoundException($name, $this);
  }

  /**
   * @return PatternInterface[]
   */
  public function getPatterns() {
    if (!isset($this->patterns)) $this->findPatterns();
    return $this->patterns;
  }

  public function getSourceFile($path) {
    return new \SplFileInfo($this->makePath("source/$path"));
  }

  public function getSourcesPath() {
    return $this->makePath("source");
  }

  public function getTemplatePath($name) {
    return $this->getPattern($name)->getTemplatePath();
  }

  public function hasFile($path) {
    return $this->getFile($path)->isFile();
  }

  public function hasSourceFile($path) {
    return $this->getFile("source/$path")->isFile();
  }

  protected function findAssets() {
    $this->assets = [];
    foreach ($this->getAssetsIterator() as $asset) {
      $path = $this->getPublicPath($asset);
      if ($this->isAssetPath($path)) {
        $relativePath = $this->getRelativeFilePath($asset, $this->getSourcesPath());
        $this->assets[$relativePath] = $asset->getRealPath();
      }
    }
  }

  protected function findPatterns() {
    if (!$patternsPath = realpath($this->makePath("source/_patterns"))) return;
    foreach ($this->getTemplateFilesInPath($patternsPath) as $file) {
      $this->patterns[] = new Pattern($file->getRealPath(), $this->getRelativeFilePath($file, $patternsPath));
    }
  }

  protected function getAssetsIterator() {
    return new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getSourcesPath(), \FilesystemIterator::SKIP_DOTS));
  }

  protected function getPublicPath(\SplFileInfo $file) {
    $prefix = realpath($this->getSourcesPath()) . DIRECTORY_SEPARATOR;
    $path = $file->getRealPath();
    return substr($path, 0, strlen($prefix)) == $prefix ? substr($path, strlen($prefix) - 1) : $path;
  }

  protected function getRelativeFilePath(\SplFileInfo $file, $dir = null) {
    if (!isset($dir)) $dir = $this->path;
    return substr($file->getRealPath(), strlen(realpath($dir)) + 1);
  }

  /**
   * @param $path
   * @return \SplFileInfo[]
   */
  protected function getTemplateFilesInPath($path) {
    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
    $matches = new \RegexIterator($iterator, '/\.twig$/', \RegexIterator::MATCH);
    return iterator_to_array($matches, true);
  }

  protected function isAssetPath($path) {
    if (substr($path, 0, 12) == '/styleguide/') return false;
    return !preg_match("|/[\._]|", $path);
  }

  protected function makePath($path) {
    return "{$this->path}/$path";
  }
}