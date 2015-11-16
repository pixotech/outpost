<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images;

abstract class ImageTestCase extends \PHPUnit_Framework_TestCase {

  protected $temporaryLocalFiles = [];

  public static function assertDimensions($path, $width, $height, $message = '') {
    self::assertThat($path, self::isDimensions($width, $height), $message);
  }

  public static function isDimensions($width, $height) {
    return new IsDimensionsConstraint($width, $height);
  }

  public function tearDown() {
    foreach ($this->temporaryLocalFiles as $path) {
      if (file_exists($path)) unlink($path);
    }
  }

  protected function getColorAtPosition($imagePath, $x, $y) {
    $image = imagecreatefromjpeg($imagePath);
    $index = imagecolorat($image, $x, $y);
    return imagecolorsforindex($image, $index);
  }

  protected function makeLocalFile($width = 100, $height = 100) {
    $path = $this->makeLocalFilePath();
    $image = new TestImageFile($width, $height);
    $image->write($path);
    return $path;
  }

  protected function makeLocalFilePath() {
    $path = tempnam(sys_get_temp_dir(), 'test-image-');
    $this->temporaryLocalFiles[] = $path;
    return $path;
  }
}

