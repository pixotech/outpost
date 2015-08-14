<?php

namespace Outpost\Html;

use Outpost\RenderableInterface;
use Outpost\ResourceInterface;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class Page extends Document implements RenderableInterface, ResourceInterface {

  public function __invoke(SiteInterface $site) {
    $this->setBody($site->render($this));
    return new Response($this->getResponseContent(), $this->getResponseStatusCode(), $this->getResponseHeaders());
  }

  public function getResponseContent() {
    return $this->toString();
  }

  public function getResponseHeaders() {
    return [];
  }

  public function getResponseStatusCode() {
    return 200;
  }

  public function getTemplateVariables() {
    $thisClass = new \ReflectionObject($this);
    $vars = [];
    foreach ($thisClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
      $vars[$property->getName()] = $property->getValue($this);
    }
    return $vars;
  }
}