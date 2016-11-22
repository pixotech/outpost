<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

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
     * @return LoggerInterface
     */
    public function getLog();

    /**
     * @return Routing\RouterInterface
     */
    public function getRouter();

    /**
     * @return \Outpost\Files\TemplateFile[]
     */
    public function getTemplates();

    /**
     * @return \Twig_Environment
     */
    public function getTwig();

    /**
     * @param string $message
     * @param mixed $level
     */
    public function log($message, $level = null);

    /**
     * @param \Exception $error
     */
    public function recover(\Exception $error);

    /**
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function render($template, array $variables = []);

    /**
     * @param Request $request
     */
    public function respond(Request $request);
}
