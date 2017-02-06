<?php

namespace Outpost;

use GuzzleHttp\ClientInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Outpost\Events\EventInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stash\Driver\Ephemeral;
use Stash\Pool;
use Symfony\Component\HttpFoundation\Request;

class SiteMock implements SiteInterface
{
    public $cache;

    public $log;

    public $resources = [];

    public $templates = [];

    public $twig;

    public function get(callable $resource)
    {
        if (!empty($this->resources)) {
            return array_shift($this->resources);
        } else {
            return call_user_func($resource);
        }
    }

    public function getCache()
    {
        if (!isset($this->cache)) {
            $this->cache = new Pool(new Ephemeral());
        }
        return $this->cache;
    }

    public function getHttpClient()
    {
        // TODO: Implement getHttpClient() method.
    }

    public function getLog()
    {
        if (!isset($this->log)) {
            $this->log = new Logger('outpost-test', [new TestHandler()]);
        }
        return $this->log;
    }

    public function getRouter()
    {
        // TODO: Implement getRouter() method.
    }

    public function getTemplates()
    {
        // TODO: Implement getTemplates() method.
    }

    public function getTwig()
    {
        if (!isset($this->twig)) {
            $this->twig = new \Twig_Environment(new \Twig_Loader_Array($this->templates));
        }
        return $this->twig;
    }

    public function log($message, $level = null, $context = [])
    {
        if ($message instanceof EventInterface) {
            $context['event'] = $message;
            $level = $message->getLogLevel();
            $message = $message->getLogMessage();
        }
        if (!isset($level)) {
            $level = LogLevel::INFO;
        }
        $this->getLog()->log($level, $message, $context);
    }

    public function recover(\Exception $error)
    {
        // TODO: Implement recover() method.
    }

    public function render($template, array $context = [])
    {
        return $this->getTwig()->render($template, $context);
    }

    public function respond(Request $request)
    {
        // TODO: Implement respond() method.
    }

    public function setCache($cache)
    {
        if (is_array($cache)) {
            foreach ($cache as $key => $value) {

            }
        }
    }

    public function setHttpResponses($responses)
    {
        if (is_array($responses)) {

        }
    }

    public function setTemplates($templates)
    {
        if (is_array($templates)) {
            $this->templates = $templates;
        }
    }
}