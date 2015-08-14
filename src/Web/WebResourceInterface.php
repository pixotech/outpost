<?php

namespace Outpost\Web;

use Outpost\ResourceInterface;

interface WebResourceInterface extends ResourceInterface {

  /**
   * @return \Outpost\Web\Requests\Request
   */
  public function getRequest();
}