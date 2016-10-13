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

class Site implements SiteInterface, \ArrayAccess
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
     * @var \Twig_Environment
     */
    protected $twig;

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
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        if (!isset($this->twig)) $this->twig = $this->makeTwigParser();
        return $this->twig;
    }

    /**
     * @deprecated
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, array $parameters = [])
    {
        user_error("URL generation is deprecated", E_USER_DEPRECATED);
        $router = $this->getRouter();
        if (!($router instanceof Router)) {
            throw new \Exception("Routing shortcuts are only allowed with Phroute routing");
        }
        return '/' . $router->getRouter()->route($name, $parameters);
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
     * @deprecated
     */
    public function offsetExists($key)
    {
        throw new \BadMethodCallException("Not supported");
    }

    /**
     * @deprecated
     */
    public function offsetGet($key)
    {
        user_error("Deprecated", E_USER_DEPRECATED);
        return null;
    }

    /**
     * @deprecated
     * @param string $path
     * @param mixed $responder
     */
    public function offsetSet($path, $responder)
    {
        user_error("Deprecated: Use Router::route() instead", E_USER_DEPRECATED);
        $router = $this->getRouter();
        if (!($router instanceof Router)) {
            throw new \Exception("Routing shortcuts are only allowed with Phroute routing");
        }
        $name = null;
        if (is_array($responder) && !is_callable($responder)) {
            $name = $path;
            list($path, $responder) = each($responder);
        }
        if (!is_callable($responder)) {
            throw new \InvalidArgumentException();
        }
        if ($pos = strpos($path, ' ')) {
            $method = substr($path, 0, $pos);
            $path = ltrim(substr($path, $pos));
        } else {
            $method = 'GET';
        }
        $router->route($method, $path, $responder, $name);

    }

    /**
     * @deprecated
     */
    public function offsetUnset($key)
    {
        throw new \BadMethodCallException("Not supported");
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
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function render($template, array $variables = [])
    {
        return $this->getTwig()->render($template, $variables);
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
     * @return array
     */
    protected function getTwigOptions()
    {
        return [];
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

    /**
     * @return \Twig_LoaderInterface
     */
    protected function makeTwigLoader()
    {
        return new \Twig_Loader_Array([]);
    }

    /**
     * @return \Twig_Environment
     */
    protected function makeTwigParser()
    {
        return new \Twig_Environment($this->makeTwigLoader(), $this->getTwigOptions());
    }
}
