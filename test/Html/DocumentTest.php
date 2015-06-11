<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Html;

class DocumentTest extends \PHPUnit_Framework_TestCase {

  public function testBody() {
    $body = 'This is the body of the document';
    $title = 'This is the title of the document';
    $document = new Document($body, $title);
    $this->assertEquals($body, $document->getBody());
  }

  public function testTitle() {
    $body = 'This is the body of the document';
    $title = 'This is the title of the document';
    $document = new Document($body, $title);
    $this->assertEquals($title, $document->getTitle());
  }
}