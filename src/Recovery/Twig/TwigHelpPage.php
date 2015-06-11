<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery\Twig;

use Outpost\Recovery\Code\Excerpt;
use Outpost\Recovery\HelpPage;

class TwigHelpPage extends HelpPage {

  public function respond(\Exception $exception, $body = '') {

    $msg = "/Unable to find template \"([^\"]+)\" \(looked into: ([^\)]+)\) in \"([^\"]+)\" at line ([0-9]+)\./";
    if (preg_match($msg, $exception->getMessage(), $m)) {
      list(, $template, $directory, $file, $line) = $m;
      $vars = array(
        'template' => $template,
        'directory' => $directory,
        'file' => "$directory/$file",
        'line' => $line,
      );
      $body .= $this->makeCodeExcerpt("$directory/$file", $line);
    }
    else {
      echo "NO MATCH";
    }

    $trace = $exception->getTrace();
    if ($trace[0]['function'] == 'findTemplate') {
      $templateName = $trace[0]['args'][0];
    }
    else {
      $templateName = "<em>Unknown</em>";
    }
    if ($trace[4]['args'][0] instanceof \Twig_Environment) {
      $templateFile = 'FILE';
    }
    else {
      $templateFile = "<em>Unknown File</em>";
    }
    $body .= "Template not found: $templateName $templateFile";

    return $body;
  }

  protected function makeCodeExcerpt($file, $line) {
    return new Excerpt($file, $line);
  }
}