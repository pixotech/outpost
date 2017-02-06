<?php

namespace Outpost\Responders;

use Outpost\SiteMock;
use Symfony\Component\HttpFoundation\Request;

class ResponderTest extends \PHPUnit_Framework_TestCase
{
    public function testResource()
    {
        $site = new SiteMock();
        $request = new Request();
        $content = "THIS IS THE CONTENT";
        $callback = function () use ($content) { return $content; };
        $responder = new Responder($site);
        $responder->get($callback);
        $this->assertEquals($content, $responder($site, $request));
    }

    /**
     * @expectedException \Outpost\Responders\ResourceException
     */
    public function testResourceUnavailable()
    {
        $site = new SiteMock();
        $request = new Request();
        $callback = function () { throw new \Exception(); };
        $responder = new Responder($site);
        $responder->get($callback);
        $responder($site, $request);
    }

    public function testRender()
    {
        $site = new SiteMock();
        $request = new Request();
        $template = "TEMPLATE NAME";
        $content = "THIS IS THE CONTENT";
        $site->setTemplates([$template => $content]);
        $responder = new Responder($site);
        $responder->render($template);
        $this->assertEquals($content, $responder($site, $request));
    }

    /**
     * @expectedException \Outpost\Responders\RenderException
     */
    public function testRenderError()
    {
        $site = new SiteMock();
        $request = new Request();
        $template = "TEMPLATE NAME";
        $responder = new Responder($site);
        $responder->render($template);
        $responder($site, $request);
    }

    public function testChain()
    {
        $site = new SiteMock();
        $request = new Request();
        $content = "THIS IS THE CONTENT";
        $template = "TEMPLATE NAME";
        $site->setTemplates([$template => "{{content}}"]);
        $callback = function () use ($content) { return ['content' => $content]; };
        $responder = new Responder($site);
        $responder->get($callback)->render($template);
        $this->assertEquals($content, $responder($site, $request));
    }
}