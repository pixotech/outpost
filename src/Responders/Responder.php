<?php

namespace Outpost\Responders;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Responder implements ResponderInterface
{
    protected $tasks = [];

    /**
     * @param SiteInterface $site
     * @param Request $request
     * @return mixed
     */
    public function __invoke(SiteInterface $site, Request $request)
    {
        return $this->respond($site, $request);
    }

    /**
     * @param callable $resource
     * @return $this
     */
    public function get(callable $resource)
    {
        $this->tasks[] = new ResourceTask($resource);
        return $this;
    }

    /**
     * @param $template
     * @return $this
     */
    public function render($template)
    {
        $this->tasks[] = new RenderTask($template);
        return $this;
    }

    /**
     * @param SiteInterface $site
     * @param Request $request
     * @return mixed
     */
    public function respond(SiteInterface $site, Request $request)
    {
        return $this->makeResponse($site, $request);
    }

    /**
     * @param SiteInterface $site
     * @param Request $request
     * @return Response
     */
    protected function makeResponse(SiteInterface $site, Request $request)
    {
        $response = null;
        foreach ($this->tasks as $task) {
            $response = call_user_func($task, $site, $request, $response);
        }
        return $response;
    }
}