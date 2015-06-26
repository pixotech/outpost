<?php

namespace Outpost\Responders\Responses;

interface RenderableResponseInterface {

  /**
   * @param \Twig_Environment $twig
   * @return mixed
   */
  public function render(\Twig_Environment $twig);
}