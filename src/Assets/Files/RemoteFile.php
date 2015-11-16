<?php

namespace Outpost\Assets\Files;

use GuzzleHttp\Client;

class RemoteFile extends File implements RemoteFileInterface {

  protected static $client;

  protected static function makeClient() {
    $options = ['defaults' => ['stream' => true]];
    return new Client($options);
  }

  public function put($path) {
    $response = $this->getClient()->get($this->getUrl());
    $body = $response->getBody();
    $fp = fopen($path, 'wb');
    while (!$body->eof()) fwrite($fp, $body->read(1024));
    fclose($fp);
  }

  protected function getClient() {
    if (!isset(self::$client)) self::$client = self::makeClient();
    return self::$client;
  }
}