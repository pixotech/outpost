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
use Monolog\Logger;
use Outpost\Events\EventMessage;

class LogFormatter implements FormatterInterface {

  public function format(array $record) {

    if (!empty($record['context'])) {
      $context = $record['context'];
      if (!empty($context['event'])) {
        $message = new EventMessage($context['event']);
        Colors::enable();
        return Colors::colorize($message->toString() . "\n");
      }
    }

    $time = $record['datetime']->format('H:i:s');
    $date = $record['datetime']->format('ymd');
    $message = $record['message'];
    $request = $this->getRequestLine($record);
    $system = !empty($record['context']['outpost']) ? $record['context']['outpost'] : null;

    if ($system == 'error') {
      if (!empty($record['context']['exception'])) {
        /** @var \Exception $exception */
        $exception = $record['context']['exception'];
        $file = $exception->getFile();
        $line = $exception->getLine();
      }
      elseif (!empty($record['context']['file'])) {
        $file = $record['context']['file'];
        $line = $record['context']['line'];
      }
      if (!empty($file)) {
        $message .= " ($file, line $line)";
      }
    }

    $color = $this->getLevelColor($record['level']);
    if ($system) {
      $str  = $this->colorize($time, $color);
      $str .= '  ' . $this->colorize(' ' . str_pad(strtoupper($system), 10) . ' ', $color, true);
      $str .= '  ' . $this->colorize("$message [$request] [$date $time]", $color);
    }
    else {
      $str = $this->colorize("{$time}  $message [$request] [$date $time]", $color);
    }
    $str .= "\n";
    return Colors::colorize($str, true);
  }

  public function formatBatch(array $records) {
    foreach ($records as $key => $record) {
      $records[$key] = $this->format($record);
    }
    return $records;
  }

  protected function getLevelColor($level) {
    switch ($level) {
      case Logger::WARNING:
        return 'yellow';
      case Logger::ERROR:
      case Logger::CRITICAL:
      case Logger::ALERT:
      case Logger::EMERGENCY:
        return 'red';
      case Logger::DEBUG:
      case Logger::INFO:
      case Logger::NOTICE:
      default:
        return 'white';
    }
  }

  protected function getRequestLine($record) {
    if (!isset($record['extra']['http_method'])) return null;
    $method = $record['extra']['http_method'];
    $server = $record['extra']['server'];
    $url = $record['extra']['url'];
    return "{$method} {$server}{$url}";
  }

  protected function colorize($string, $color, $inverse = false) {
    $endCode = "%n";
    switch ($color) {
      case 'cyan':
        $startCode = $inverse ? "%6%K" : "%c";
        break;
      case 'red':
        $startCode = $inverse ? "%1%W" : "%r";
        break;
      case 'yellow':
        $startCode = $inverse ? "%3%K" : "%y";
        break;
      default:
        $startCode = "%n";
    }
    return $startCode . $string . $endCode;
  }

  protected function makeColoredMessage($message, $level) {
    return isset($color) ? ($color . $message . "%n") : $message;
  }
}