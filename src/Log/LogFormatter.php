<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Log;

use cli\Colors;
use Monolog\Formatter\FormatterInterface;

class LogFormatter implements FormatterInterface {

  public function format(array $record) {
    $str = '';
    $lines = [];
    if ($record['level_name'] == 'ERROR') {
      $lines[] = sprintf("%%RError:%%n %s", $record['message']);
      if (!empty($record['context'])) {
        list($file, $line) = $record['context'];
        $lines[] = "%y$file, line $line%n";
      }
    }
    else {
      $lines[] = sprintf("[%s] %s", $record['level_name'], $record['message']);
    }
    $lines[] = $this->getRequestLine($record);
    $str .= $this->formatLines($lines, $record['datetime']);
    return $this->colorize($str);
  }

  public function formatBatch(array $records) {
    foreach ($records as $key => $record) {
      $records[$key] = $this->format($record);
    }
    return $records;
  }

  protected function getRequestLine($record) {
    $method = $record['extra']['http_method'];
    $server = $record['extra']['server'];
    $url = $record['extra']['url'];
    return "%y{$method} {$server}{$url}%n";
  }

  protected function colorize($string) {
    return Colors::colorize($string, true);
  }

  protected function formatLines($lines, \DateTime $date) {
    $output = '';
    $timestamp = $date->format('Y-m-d H:i:s');
    foreach ((array)$lines as $i => $line) {
      $prefix = $i ? str_repeat(' ', strlen($timestamp)) : $timestamp;
      $output .= "$prefix  $line\n";
    }
    return $output . "\n";
  }

}