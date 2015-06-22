<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use Outpost\Assets\StorageInterface;
use Outpost\Assets\FileInterface;
use Outpost\Web\ClientInterface;
use Outpost\Web\Requests\FileRequest;

class RemoteImage extends Image {

  protected $url;

  public function __construct(ClientInterface $client, $url, $alt = '') {
    parent::__construct($alt);
    $this->client = $client;
    $this->url = $url;
  }

  public function getExtension() {
    return pathinfo($this->url, PATHINFO_EXTENSION);
  }

  public function getKey() {
    return self::makeKey(__CLASS__, $this->url);
  }

  public function generate(FileInterface $file, StorageInterface $storage) {
    $body = $this->getClient()->send($this->makeRequest());
    $fp = fopen($file->getPath(), 'wb');
    while (!$body->eof()) fwrite($fp, $body->read(1024));
    fclose($fp);
  }

  public function makeRequest() {
    return new FileRequest($this->url);
  }

  protected function getClient() {
    return $this->client;
  }
}