<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery;

use Outpost\Html\Document;
use Outpost\Recovery\Code\Excerpt;
use Outpost\Recovery\Trace\Stack;
use Outpost\SiteInterface;

class HelpPage {

  protected $exception;

  public static function getOutpostPath() {
    return realpath(__DIR__ . '/..');
  }

  public static function isOutpostPath($path) {
    $prefix = self::getOutpostPath() . '/';
    return substr(realpath($path), 0, strlen($prefix)) == $prefix;
  }

  public static function makeExceptionString(\Exception $e) {
    return sprintf("%s (%s, line %d)", $e->getMessage(), $e->getFile(), $e->getLine());
  }

  public function __construct(\Exception $exception, SiteInterface $site = null) {
    $this->exception = $exception;
    if (isset($site)) $this->site = $site;
  }

  public function __toString() {
    try {
      return (string)$this->makePage();
    }
    catch (\Exception $e) {
      return self::makeExceptionString($e);
    }
  }

  protected function makePage() {
    if ($previous = $this->exception->getPrevious()) {
      $page = new HelpPage($previous);
      return $page->makePage();
    }
    $vars = [
      'title'=> $this->makeTitle(),
      # 'excerpt' => $this->makeCodeExcerpt(),
      'exception' => $this->exception,
      'trace' => @\Kint::trace($this->exception->getTrace()),
    ];
    if ($this->exception instanceof HasDescriptionInterface) {
      $vars['title'] = null;
      $vars['description'] = $this->exception->getDescription();
    }
    else {
      $vars['description'] = '<h1>' . $this->exception->getMessage() . '</h1>';
    }
    if ($this->exception instanceof HasRepairInterface) {
      $vars['repairInstructions'] = $this->exception->getRepair();
    }
    $document = new Document($this->render("page.php", $vars), 'ATTENTION');
    $document->addStyles(file_get_contents(__DIR__ . '/templates/help.css'));
    return $document;
  }

  protected function makeCodeExcerpt() {
    return new Excerpt($this->exception->getFile(), $this->exception->getLine());
  }

  protected function makeTitle() {
    if ($message = $this->exception->getMessage()) return $message;
    $exceptionClass = new \ReflectionClass($this->exception);
    return $exceptionClass->getShortName();
  }

  protected function makeTrace() {
    return new Stack($this->exception);
  }

  protected function isOutpostException() {
    return self::isOutpostPath($this->exception->getFile());
  }

  protected function render($template, array $variables = []) {
    extract($variables);
    ob_start();
    include __DIR__ . "/templates/$template";
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }

  protected function debug($var) {
    return @\Kint::dump($var);
  }
}