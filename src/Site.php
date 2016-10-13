<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Monolog\Logger;
use Outpost\Content\Factory as ContentFactory;
use Outpost\Resources\CacheableInterface;
use Outpost\Recovery\HelpPage;
use Outpost\Routing\Router;
use Outpost\Routing\RouterInterface;
use Psr\Log\LogLevel;
use Stash\Driver\Ephemeral;
use Stash\Pool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Site implements SiteInterface
{
    /**
     * @var Pool
     */
    protected $cache;

    /**
     * @var ContentFactory
     */
    protected $content;

    /**
     * @var Logger
     */
    protected $log;

    /**
     * @var Routing\RouterInterface
     */
    protected $router;

    /**
     * @param int $code
     * @param string|null $message
     * @return string
     */
    public static function makeHttpStatusHeader($code, $message = null)
    {
        if (!isset($message)) $message = static::makeHttpStatusMessage($code);
        return sprintf("HTTP/1.1 %s %s", $code, $message);
    }

    /**
     * @param int $code
     * @return string
     */
    public static function makeHttpStatusMessage($code)
    {
        return isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '';
    }

    /**
     * Shorthand for Site::get()
     *
     * @param callable $resource
     * @return mixed
     */
    public function __invoke(callable $resource)
    {
        return $this->get($resource);
    }

    /**
     * Get a site resource
     *
     * @param callable $resource
     * @return mixed
     */
    public function get(callable $resource)
    {
        if ($resource instanceof CacheableInterface) {
            $key = $resource->getCacheKey();
            $lifetime = $resource->getCacheLifetime();
            /** @var callable $resource */
            $cached = $this->getCache()->getItem($key);
            $result = $cached->get();
            if ($cached->isMiss()) {
                $this->log(sprintf("Not found: %s", $key), LogLevel::NOTICE);
                $cached->lock();
                $result = call_user_func($resource, $this);
                $cached->set($result, $lifetime);
            }
            else {
                $this->log(sprintf("Found: %s", $key));
            }
        } else {
            $result = call_user_func($resource, $this);
        }
        return $result;
    }

    /**
     * @return Pool
     */
    public function getCache()
    {
        if (!isset($this->cache)) $this->cache = $this->makeCache();
        return $this->cache;
    }

    /**
     * @return ContentFactory
     */
    public function getContent()
    {
        if (!isset($this->content)) $this->content = $this->makeContentFactory();
        return $this->content;
    }

    /**
     * @return Logger
     */
    public function getLog()
    {
        if (!isset($this->log)) $this->log = $this->makeLog();
        return $this->log;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        if (!isset($this->router)) $this->router = $this->makeRouter();
        return $this->router;
    }

    /**
     * @param string $message
     * @param string $level
     */
    public function log($message, $level = null)
    {
        if (!isset($level)) $level = LogLevel::INFO;
        $this->getLog()->log($level, $message);
    }

    /**
     * @param \Exception $e
     * @param string $level
     */
    public function logException(\Exception $e, $level = LogLevel::ERROR)
    {
        $eClass = get_class($e);
        $message = sprintf("%s: %s (%s, line %d", $eClass, $e->getMessage(), $e->getFile(), $e->getLine());
        $this->log($message, $level);
    }

    /**
     * @param string $className
     * @param array $variables
     * @return mixed
     */
    public function make($className, array $variables)
    {
        return $this->getContent()->create($className, $variables);
    }

    /**
     * @param \Exception $error
     * @return Response
     */
    public function recover(\Exception $error)
    {
        $this->logException($error);
        return $this->makeHelpResponse($error);
    }

    /**
     * @param Request $request
     */
    public function respond(Request $request)
    {
        try {
            $this->logRequest($request);
            $response = call_user_func($this->getResponder($request), $this, $request);
        } catch (\Exception $error) {
            $response = $this->recover($error);
        }
        if ($response instanceof Response) {
            $response->prepare($request);
            $response->send();
        }
    }

    /**
     * @param int $code
     * @param string|null $message
     */
    public function sendStatus($code, $message = null)
    {
        header(static::makeHttpStatusHeader($code, $message));
    }

    /**
     * @return \Stash\Interfaces\DriverInterface
     */
    protected function getCacheDriver()
    {
        return new Ephemeral();
    }

    /**
     * @return array
     */
    protected function getLogHandlers()
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getLogName()
    {
        return 'outpost';
    }

    /**
     * @param Request $request
     * @return callable
     */
    protected function getResponder(Request $request)
    {
        return $this->getRouter()->getResponder($request);
    }

    /**
     * @param Request $request
     */
    protected function logRequest(Request $request)
    {
        $this->log("Request received: " . $request->getPathInfo());
    }

    /**
     * @return Pool
     */
    protected function makeCache()
    {
        return new Pool($this->getCacheDriver());
    }

    /**
     * @return ContentFactory
     */
    protected function makeContentFactory()
    {
        return new ContentFactory();
    }

    /**
     * @param \Exception $error
     * @param int $status
     * @return Response
     */
    protected function makeHelpResponse(\Exception $error, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        try {
            $content = (string)new HelpPage($error);
        } catch (\Exception $e) {
            $content = $e->getMessage();
        }
        return new Response($content, $status);
    }

    /**
     * @return Logger
     */
    protected function makeLog()
    {
        return new Logger($this->getLogName(), $this->getLogHandlers());
    }

    /**
     * @return Router
     */
    protected function makeRouter()
    {
        return new Router();
    }
}
