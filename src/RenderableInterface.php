<?php

namespace Outpost;

interface RenderableInterface {
  public function getTemplate();
  public function getTemplateVariables();
}