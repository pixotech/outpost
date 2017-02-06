<?php

namespace Outpost;

use Symfony\Component\HttpFoundation\Request;

class TestCase
{
    protected function makeRequest($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
    }

    protected function withCache($cache)
    {
        if (is_array($cache)) {

        }
    }

    protected function withHttpResponses($responses)
    {
        if (is_array($responses)) {

        }
    }

    protected function withTemplates($templates)
    {
        if (is_array($templates)) {

        }
    }
}