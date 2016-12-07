<?php

namespace Outpost\Log;

use cli\Colors;
use Monolog\Formatter\FormatterInterface;
use Outpost\Events\EventMessage;

class Formatter implements FormatterInterface
{
    public function format(array $record)
    {
        $record = new Record($record);
        if ($record->hasContext()) {
            $context = $record->getContext();
            if (!empty($context['event'])) {
                $message = new EventMessage($context['event']);
                return (string)$message;
            }
        }

        $message = $record->getMessage();

        if ($record->getSystem() == 'error') {
            if (!empty($record['context']['exception'])) {
                /** @var \Exception $exception */
                $exception = $record['context']['exception'];
                $file = $exception->getFile();
                $line = $exception->getLine();
            } elseif (!empty($record['context']['file'])) {
                $file = $record['context']['file'];
                $line = $record['context']['line'];
            }
            if (!empty($file)) {
                $message .= " ($file, line $line)";
            }
        }

        $timestamp = new Timestamp($record->getTime());
        $level = str_pad($record->getLevelName(), 12, '.');
        return "$timestamp  $level $message\n";
    }

    public function formatBatch(array $records)
    {
        foreach ($records as $key => $record) {
            $records[$key] = $this->format($record);
        }
        return $records;
    }

    protected function colorize($string, $color, $inverse = false)
    {
        $endCode = "%n";
        switch ($color) {
            case 'cyan':
                $startCode = $inverse ? "%6%W" : "%c";
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

    protected function makeColoredMessage($message, $level)
    {
        return isset($color) ? ($color . $message . "%n") : $message;
    }
}
