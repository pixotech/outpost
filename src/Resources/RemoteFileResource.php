<?php

namespace Outpost\Resources;

class RemoteFileResource extends RemoteResource {

  protected function getRequestOptions() {
    return ['stream' => true];
  }
}