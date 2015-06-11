<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Command;

class Responder {

  protected $directory;
  protected $environment;
  protected $simulate = false;

  public function __construct() {
    \cli\Colors::enable();
  }

  public function __invoke() {
    $this->respond();
  }

  public function respond() {
    $input = $this->getInput();
    $this->adjustSettings($input);
    switch ($input[0]) {
      case null;
        \cli\line("Enter a command");
        break;
      default:
        \cli\line("Unknown command");
    }
  }

  protected function adjustSettings($input) {
    $this->directory = $input['directory'];
    $this->environment = $input['environment'];
    $this->simulate = $input['simulate'];
  }

  protected function getInput() {
    $input = new \Commando\Command();

    $input->option('directory')->alias('dir')->alias('d')
      ->describedAs('The root directory of the Outpost site')
      ->require();

    $input->option('environment')->alias('env')->alias('e')
      ->describedAs('The site environment')
      ->default('production');

    $input->option('simulate')->alias('sim')->alias('s')
      ->describedAs('Simulate')
      ->boolean();

    return $input;
  }
}