<?php

namespace Outpost\Command;

class Deleter {

  protected $directory;

  public function __construct($directory) {
    $this->directory = $directory;
  }

  public function delete() {
    $this->deleteContents();
    $this->deleteDirectory($this->directory);
  }

  public function deleteContents() {
    foreach ($this->getContents() as $item) {
      if ($item->isDir()) $this->deleteDirectory($item);
      else if ($item->isFile()) $this->deleteFile($item);
    }
  }

  /**
   * @return \SplFileInfo[]
   */
  public function getContents() {
    $contents = [];
    $directory = new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS);
    foreach (new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
      $contents[] = $file;
    }
    return $contents;
  }

  protected function deleteDirectory(\SplFileInfo $dir) {
    if (!rmdir($dir->getPathname())) throw new \Exception("Could not delete directory: " . $dir->getPathname());
  }

  protected function deleteFile(\SplFileInfo $file) {
    if (!unlink($file->getPathname())) throw new \Exception("Could not delete file: " . $file->getPathname());
  }
}