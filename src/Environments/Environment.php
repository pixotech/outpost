<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Symfony\Component\HttpFoundation\Request;

class Environment implements EnvironmentInterface {

  protected $configuration;
  protected $rootDirectory;
  protected $timezone = 'America/Chicago';

  public function __construct($root, Request $request = null) {
    if (!is_dir($root)) {
      throw new Exceptions\InvalidPathException($root);
    }
    $this->rootDirectory = realpath($root);
    $this->request = $request ?: Request::createFromGlobals();
    $this->adjust();
  }

  public function getCacheDriver() {
    return null;
  }

  public function getLogHandlers() {
    return [];
  }

  public function getRequest() {
    return $this->request;
  }

  public function getRootDirectory() {
    return $this->rootDirectory;
  }

  public function getTwigLoader() {

  }

  public function getTwigOptions() {

  }

  protected function adjust() {
    $this->configureErrorReporting();
    $this->configureTimezone();
  }

  protected function configureErrorReporting() {
    ini_set('display_errors', 0);
    error_reporting(0);
  }

  protected function configureTimezone() {
    date_default_timezone_set($this->timezone);
  }

  protected function ensureDirectory($dir) {
    if (!is_dir($dir)) {
      $this->ensureDirectory(dirname($dir));
      mkdir($dir);
    }
  }
}