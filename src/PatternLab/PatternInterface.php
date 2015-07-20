<?php

namespace Outpost\PatternLab;

interface PatternInterface {
  public function getShorthand();
  public function getTemplatePath();
  public function matches($name);
}
