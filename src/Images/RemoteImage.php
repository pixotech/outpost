<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use GuzzleHttp\Client;
use Outpost\Assets\StorageInterface;
use Outpost\Assets\FileInterface;

class RemoteImage extends Image {

  protected $url;

  public function __construct($url, $alt = '') {
    parent::__construct($alt);
    $this->url = $url;
  }

  public function getKey() {
    return self::makeKey(__CLASS__, $this->url);
  }

  public function generate(FileInterface $file, StorageInterface $storage) {
    $fp = fopen($file->getPath(), 'wb');
    $body = $this->getResponse()->getBody();
    while (!$body->eof()) fwrite($fp, $body->read(1024));
    fclose($fp);
  }

  protected function getClient() {
    return new Client();
  }

  protected function getResponse() {
    $client = $this->getClient();
    return $client->get($this->url, ['stream' => true]);
  }
}