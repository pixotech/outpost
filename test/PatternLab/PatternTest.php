<?php

namespace Outpost\PatternLab;

class PatternTest extends \PHPUnit_Framework_TestCase {

  public function testRemoveOrdering() {
    $segment = '02-landscape-16x9';
    $this->assertEquals('landscape-16x9', Pattern::removeOrderingFromPathSegment($segment));
  }

  public function testDontRemoveOrderingWithoutNumbers() {
    $segment = '-landscape-16x9';
    $this->assertEquals($segment, Pattern::removeOrderingFromPathSegment($segment));
  }

  public function testDontRemoveOrderingWithoutHyphen() {
    $segment = '02landscape-16x9';
    $this->assertEquals($segment, Pattern::removeOrderingFromPathSegment($segment));
  }

  public function testExplodePath() {
    $path = "00-atoms/03-images/02-landscape-16x9";
    $this->assertEquals(['atoms', 'images', 'landscape-16x9'], Pattern::explodePath($path));
  }

  public function testGetTemplatePath() {
    $path = "00-atoms/03-images/02-landscape-16x9";
    $templatePath = "/path/to/patternlab/patterns/$path.twig";
    $pattern = new Pattern($templatePath, $path);
    $this->assertEquals($templatePath, $pattern->getTemplatePath());
  }

  public function testGetShorthand() {
    $path = "00-atoms/03-images/02-landscape-16x9.twig";
    $templatePath = "/path/to/patternlab/patterns/$path";
    $pattern = new Pattern($templatePath, $path);
    $this->assertEquals("atoms-landscape-16x9", $pattern->getShorthand());
  }
}