<?php

namespace Outpost\Content\Documentor;

use Outpost\Content\Reflection\FileReflection;
use Outpost\Files\Directory;
use Psr\Log\LoggerInterface;

class Documentor implements DocumentorInterface
{
    protected $directory;

    protected $entities = [];

    protected $log;

    protected $twig;

    public static function camelCaseToWords($str) {
        return trim(preg_replace('/([A-Z])/', ' $1', $str));
    }

    public function __construct($directory, LoggerInterface $log = null)
    {
        $this->directory = new Directory($directory);
        if (isset($log)) $this->log = $log;
        $this->findEntities();
        $this->makeTwigParser();
    }

    public function generate($dest)
    {
        if (!is_dir($dest)) {
            throw new \InvalidArgumentException("Not a directory: $dest");
        }
        $index = new Index($this);
        $content = $this->render("content/index.twig", ['index' => $index]);
        file_put_contents("$dest/index.html", $content);
        foreach ($this->getEntities() as $entity) {
            $path = "$dest/{$entity->getUrl()}";
            $content = $this->render("content/entity.twig", ['entity' => $entity, 'index' => $index]);
            file_put_contents($path, $content);
        }
    }

    public function getEntities()
    {
        return $this->entities;
    }

    protected function findEntities()
    {
        foreach ($this->getCodeFiles() as $path => $file) {
            $fileReflection = new FileReflection($path);
            foreach ($fileReflection->getClasses() as $classname) {
                $this->entities[] = new Entity($classname, $fileReflection);
            }
        }
    }

    protected function getCodeFiles()
    {
        return $this->directory->getFilesWithExtension('php');
    }

    protected function makeTwigParser()
    {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../../../templates'));
    }

    protected function render($template, array $variables = [])
    {
        return $this->twig->render($template, $variables);
    }
}
