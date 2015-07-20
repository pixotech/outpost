<?php

namespace Outpost\Web;

use Outpost\Resources\ResourceInterface;

interface WebResourceInterface extends ResourceInterface {

  /**
   * @return \Outpost\Web\Requests\Request
   */
  public function getRequest();
}