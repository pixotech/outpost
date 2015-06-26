<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Symfony\Component\HttpFoundation\Request;

interface EnvironmentInterface {

  /**
   * @return null|\Stash\Interfaces\DriverInterface
   */
  public function getCacheDriver();

  /**
   * @return \Monolog\Handler\HandlerInterface[]
   */
  public function getLogHandlers();

  /**
   * @return Request
   */
  public function getRequest();

  /**
   * @return string
   */
  public function getRootDirectory();

  /**
   * @return \Twig_LoaderInterface
   */
  public function getTwigLoader();

  /**
   * @return array
   */
  public function getTwigOptions();
}