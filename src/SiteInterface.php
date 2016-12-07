<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Stash\Pool;
use Symfony\Component\HttpFoundation\Request;

interface SiteInterface
{
    /**
     * @param callable $resource
     * @return mixed
     */
    public function get(callable $resource);

    /**
     * @return Pool
     */
    public function getCache();

    /**
     * @return ClientInterface
     */
    public function getHttpClient();

    /**
     * @return LoggerInterface
     */
    public function getLog();

    /**
     * @return Routing\RouterInterface
     */
    public function getRouter();

    /**
     * @return \Outpost\Files\TemplateFileCollection
     */
    public function getTemplates();

    /**
     * @return \Twig_Environment
     */
    public function getTwig();

    /**
     * @param string $message
     * @param int $level
     * @param array $context
     */
    public function log($message, $level = null, $context = []);

    /**
     * @param \Exception $error
     */
    public function recover(\Exception $error);

    /**
     * @param mixed $template
     * @param array $context
     * @return string
     */
    public function render($template, array $context = []);

    /**
     * @param Request $request
     */
    public function respond(Request $request);
}
