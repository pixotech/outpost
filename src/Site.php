<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Monolog\Logger;
use Outpost\Cache\Events\ItemFoundEvent;
use Outpost\Cache\Events\ItemMissingEvent;
use Outpost\Events\EventInterface;
use Outpost\Events\ExceptionEvent;
use Outpost\Events\RequestReceivedEvent;
use Outpost\Events\ResponseCompleteEvent;
use Outpost\Files\Directory;
use Outpost\Files\TemplateFile;
use Outpost\Reflection\ClassCollection;
use Outpost\Resources\CacheableInterface;
use Outpost\Recovery\HelpPage;
use Outpost\Responders\Responder;
use Outpost\Routing\Router;
use Outpost\Routing\RouterInterface;
use Outpost\Templates\RenderableInterface;
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
     * @var ClientInterface
     */
    protected $http;

    /**
     * @var ClassCollection
     */
    protected $libraryClasses;

    /**
     * @var string
     */
    protected $libraryPath;

    /**
     * @var Logger
     */
    protected $log;

    /**
     * @var Routing\RouterInterface
     */
    protected $router;

    /**
     * @var TemplateFile[]
     */
    protected $templates;

    /**
     * @var string
     */
    protected $templatesPath;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

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
                $this->log(new ItemMissingEvent($key));
                $cached->lock();
                $result = call_user_func($resource, $this);
                $cached->set($result, $lifetime);
            }
            else {
                $this->log(new ItemFoundEvent($key));
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
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (!isset($this->http)) $this->http = $this->makeHttpClient();
        return $this->http;
    }

    /**
     * @return ClassCollection
     */
    public function getLibraryClasses()
    {
        if (!isset($this->libraryClasses)) {
            if (empty($this->libraryPath)) {
                throw new \BadMethodCallException("Library path is not set");
            }
            $this->libraryClasses = $this->getLibraryDirectory()->getLibraryClasses();
        }
        return $this->libraryClasses;
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
     * @return TemplateFile[]
     */
    public function getTemplates()
    {
        if (!isset($this->templates)) {
            $this->templates = $this->getTemplatesDirectory()->getTemplateFiles();
        }
        return $this->templates;
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
     * @deprecated 1.2
     */
    public function getUrl()
    {
        user_error("URL generation is deprecated", E_USER_DEPRECATED);
    }

    /**
     * @param string $message
     * @param int $level
     * @param array $context
     */
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

    /**
     * @param \Exception $e
     * @param string $level
     */
    public function logException(\Exception $e, $level = LogLevel::ERROR)
    {
        $this->log(new ExceptionEvent($e), $level);
    }

    /**
     * @deprecated
     * @param string $key
     * @returns bool
     * @throws \BadMethodCallException
     */
    public function offsetExists($key)
    {
        throw new \BadMethodCallException("Not supported");
    }

    /**
     * @deprecated
     * @param string $key
     * @returns null
     */
    public function offsetGet($key)
    {
        user_error("Deprecated", E_USER_DEPRECATED);
        return null;
    }

    /**
     * @deprecated 1.2
     * @param string $path
     * @param mixed $responder
     */
    public function offsetSet($path, $responder)
    {
        user_error("Deprecated: Use Site::route() instead", E_USER_DEPRECATED);
    }

    /**
     * @deprecated 1.2
     * @param string $key
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
     * @param mixed $template
     * @param array $context
     * @return string
     */
    public function render($template, array $context = [])
    {
        if ($template instanceof RenderableInterface) {
            $context += $template->getTemplateContext();
            $template = $template->getTemplate();
        }
        return $this->getTwig()->render($template, $context);
    }

    /**
     * @param Request $request
     */
    public function respond(Request $request)
    {
        try {
            $this->log(new RequestReceivedEvent($request));
            $response = call_user_func($this->getResponder($request), $this, $request);
        } catch (\Exception $error) {
            $response = $this->recover($error);
        }
        if ($response instanceof Response) {
            $response->prepare($request);
            $response->send();
            $this->log(new ResponseCompleteEvent($response, $request));
        } else {
            $this->log(new ResponseCompleteEvent(new Response(), $request));
        }
    }

    public function route($path)
    {
        $router = $this->getRouter();
        if (!($router instanceof Router)) {
            throw new \BadMethodCallException("Method not available for custom routers");
        }
        $responder = new Responder();
        $router->route($path, $responder);
        return $responder;
    }

    /**
     * @param string $path
     */
    public function setLibraryPath($path)
    {
        $this->libraryPath = $path;
    }

    /**
     * @param string $path
     */
    public function setTemplatesPath($path)
    {
        $this->templatesPath = $path;
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
    protected function getHttpClientOptions()
    {
        return [];
    }

    /**
     * @return Directory
     */
    protected function getLibraryDirectory()
    {
        return $this->libraryPath ? new Directory($this->libraryPath) : null;
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
     * @return Directory
     */
    protected function getTemplatesDirectory()
    {
        return $this->templatesPath ? new Directory($this->templatesPath) : null;
    }

    /**
     * @return array
     */
    protected function getTwigOptions()
    {
        return [];
    }

    /**
     * @return Pool
     */
    protected function makeCache()
    {
        return new Pool($this->getCacheDriver());
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
     * @return Client
     */
    protected function makeHttpClient()
    {
        return new Client($this->getHttpClientOptions());
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
