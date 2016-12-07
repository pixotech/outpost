<?php

namespace Outpost\Resources;

use GuzzleHttp\Exception\GuzzleException;
use Outpost\Http\NewRequestEvent;
use Outpost\Http\ResponseReceivedEvent;
use Outpost\SiteInterface;

class HttpResource implements HttpResourceInterface
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function __invoke(SiteInterface $site)
    {
        try {
            $url = $this->getRequestUrl();
            $method = $this->getRequestMethod();
            $site->log(new NewRequestEvent($method, $url));
            $response = $site->getHttpClient()->request($method, $url, $this->getRequestOptions());
            $site->log(new ResponseReceivedEvent($method, $url, $response->getStatusCode()));
            return (string)$response->getBody();
        } catch (GuzzleException $e) {
            $site->getLog()->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @return string
     */
    protected function getRequestMethod()
    {
        return 'GET';
    }

    /**
     * @return array
     */
    protected function getRequestOptions()
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getRequestUrl()
    {
        return $this->url;
    }
}
