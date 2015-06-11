<?php

namespace Outpost\Images;

class TestImageFile {

  protected $width;
  protected $height;
  protected $number_of_shapes = 1;
  protected $image;
  protected $quality = 75;

  public function __construct($width, $height, $backgroundColor = null, $number_of_shapes=1) {
    $this->width = $width;
    $this->height = $height;
    $this->backgroundColor = isset($backgroundColor) ? $backgroundColor : $this->makeRandomColor();
    $this->number_of_shapes = $number_of_shapes;
  }

  public function __toString() {
    return $this->getUrl();
  }

  public function get($quality = null) {
    if (!isset($quality)) $quality = $this->quality;
    ob_start();
    imagejpeg($this->getImage(), NULL, $quality);
    $data = ob_get_contents();
    ob_end_clean();
    return $data;
  }

  public function getUrl($qualilty = null) {
    $data = $this->get($qualilty);
    return 'data:image/jpeg;base64,' . base64_encode($data);
  }

  public function write($path, $quality = null) {
    if (!isset($quality)) $quality = $this->quality;
    imagejpeg($this->getImage(), $path, $quality);
  }

  public function getImage() {
    if (!isset($this->image)) {

      $shapes = array('circle', 'square', 'triangle');

      $width = $this->width;
      $height = $this->height;
      $margin = min($width, $height) * .02;

      $image = imagecreatetruecolor($width, $height);

      // Background
      $canvas = imagecolorallocate($image, 255, 255, 255);
      imagefilledrectangle($image, 0, 0, $width, $height, $canvas);

      // Background
      list($r, $g, $b) = $this->backgroundColor;
      $bg_color = imagecolorallocatealpha($image, $r, $g, $b, 60);
      imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

      // Foreground shapes
      $last_shape = $last_color = $last_opacity = -100;
      for ($i=0; $i < $this->number_of_shapes; $i++) {

        // Color
        do {
          $color = mt_rand(0, 1);
        }
        while ($color == $last_color);
        $color_value = round($color * 255);
        $min_opacity = $color ? 108 : 72;
        $opacity = mt_rand($min_opacity, 120);
        $fg_color = imagecolorallocatealpha($image, $color_value, $color_value, $color_value, $opacity);

        // Shape
        do {
          $shape = $shapes[array_rand($shapes)];
        }
        while ($shape == $last_shape);

        // Size
        $size = round((min($width, $height) - ($margin * 2)) * mt_rand(25, 75)/100);
        $radius = round($size/2);

        $center_x = mt_rand($margin + $radius, $width - $margin - $radius);
        $center_y = mt_rand($margin + $radius, $height - $margin - $radius);

        switch ($shape) {
          case 'circle':
            imagefilledellipse($image, $center_x, $center_y, $size, $size, $fg_color);
            break;
          case 'square':
            imagefilledrectangle($image, $center_x - $radius, $center_y - $radius, $center_x + $radius, $center_y + $radius, $fg_color);
            break;
          case 'triangle':
            $rotation = mt_rand(1, 2);
            switch ($rotation) {

              // pointing up
              case 1:
                $points = array(
                  $center_x, $center_y - $radius,
                  $center_x - $radius, $center_y + $radius,
                  $center_x + $radius, $center_y + $radius,
                );
                break;

              // pointing down
              case 2:
                $points = array(
                  $center_x, $center_y + $radius,
                  $center_x - $radius, $center_y - $radius,
                  $center_x + $radius, $center_y - $radius,
                );
                break;

              // pointing left
              case 3:
                $points = array(
                  $center_x - $radius, $center_y,
                  $center_x + $radius, $center_y - $radius,
                  $center_x + $radius, $center_y + $radius,
                );
                break;

              // pointing right
              case 4:
                $points = array(
                  $center_x + $radius, $center_y,
                  $center_x - $radius, $center_y - $radius,
                  $center_x - $radius, $center_y + $radius,
                );
                break;
            }
            imagefilledpolygon($image, $points, 3, $fg_color);
            break;
        }

        $last_color = $color;
        $last_opacity = $opacity;
        $last_shape = $shape;
      }

      $this->image = $image;
    }
    return $this->image;
  }

  protected function makeRandomColor() {
    $color = array();
    foreach (range(1, 3) as $i) {
      $color[] = $this->makeWebSafeColorValue(1, 3);
    }
    return $color;
  }

  protected function makeWebSafeColorValue($min=0, $max=5) {
    return mt_rand($min, $max) * 51;
  }

  protected function makeComplementaryColor($r, $g, $b) {
    list($h, $s, $l) = $this->rgb2hsl($r, $g, $b);
    $h2 = $h + .5;
    if ($h2 > 1) $h2 -= 1;
    return $this->hsl2rgb($h2, $s, $l);
  }

  protected function rgb2hsl($r, $g, $b) {
    $r = $r / 255;
    $g = $g / 255;
    $b = $b / 255;
    $min = min($r, $g, $b);
    $max = max($r, $g, $b);
    $delta = $max - $min;
    $l = ($max + $min)/2;
    if ($max == 0) {
      $h = 0;
      $s = 0;
    }
    else {
      $s = ($l < .5) ? ($delta / ($max + $min)) : ($delta / (2 - $max - $min));
      $delta_r = $delta ? ((($max - $r)/6) + ($delta/2))/$delta : 0;
      $delta_g = $delta ? ((($max - $g)/6) + ($delta/2))/$delta : 0;
      $delta_b = $delta ? ((($max - $b)/6) + ($delta/2))/$delta : 0;
      if ($r == $max) $h = $delta_b - $delta_g;
      elseif ($g == $max) $h = 1/3 + $delta_r - $delta_b;
      else $h = 2/3 + $delta_g - $delta_r;
      if ($h < 0) $h++;
      if ($h > 1) $h--;
    }
    return array($h, $s, $l);
  }

  protected function hsl2rgb($h, $s, $l) {
    if ($s == 0) {
      $r = $l * 255;
      $g = $l * 255;
      $b = $l * 255;
    }
    else {
      if ($l < .5) {
        $var2 = $l * (1 + $s);
      }
      else {
        $var2 = ($l + $s) - ($s * $l);
      }
      $var1 = 2 * $l - $var2;

      $r = 255 * $this->hue2rgb($var1, $var2, $h + 1/3);
      $g = 255 * $this->hue2rgb($var1, $var2, $h);
      $b = 255 * $this->hue2rgb($var1, $var2, $h - 1/3);
    }
    return array($r, $g, $b);
  }

  protected function hue2rgb($var1, $var2, $h) {
    if ($h < 0) {
      $h++;
    }
    if ($h > 1) {
      $h--;
    }
    if (6 * $h < 1) {
      return $var1 + ($var2 - $var1) * 6 * $h;
    }
    if (2 * $h < 1) {
      return $var2;
    }
    if (3 * $h < 2) {
      return $var1 + ($var2 - $var1) * (2/3 - $h) * 6;
    }
    return $var1;
  }
}