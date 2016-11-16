<?php

namespace Outpost\Generator;

use Outpost\Generator\Files\File;
use Outpost\Generator\Files\FileEvent;
use Outpost\Generator\Files\FileInterface;
use Outpost\SiteInterface;

class Generator implements GeneratorInterface
{
    /**
     * @var bool
     */
    protected $clean = false;

    /**
     * @var bool
     */
    protected $force = true;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var SiteInterface
     */
    protected $site;

    public function __construct(SiteInterface $site, $path)
    {
        $this->site = $site;
        $this->path = $path;
    }

    public function __invoke()
    {
        $report = new Report();
        $paths = $this->getExistingPaths();
        /** @var \Outpost\Generator\Files\FileInterface $file */
        foreach ($this->getFiles() as $path => $file) {
            $report->addEvent($this->handleFile($file, $path));
            if (false !== $i = array_search($path, $paths)) {
                unset($paths[$i]);
            }
        }
        foreach ($paths as $path) {
            if ($this->clean) {
                $report->addEvent($this->deleteFile($path));
            } else {
                $event = new FileEvent($path);
                $event->stop(FileEvent::SKIPPED);
                $report->addEvent($event);
            }
        }
        $report->stop();
        return $report;
    }

    protected function deleteFile($path)
    {
        $destination = $this->makePath($path);
        if (is_file($destination)) unlink($destination);
        $event = new FileEvent($path);
        $event->stop(FileEvent::DELETED);
        return $event;
    }

    /**
     * @param $path
     */
    protected function ensureDirectory($path)
    {
        if (!is_dir($path)) mkdir($path, 0777, true);
    }

    /**
     * @param $path
     */
    protected function ensurePathDirectory($path)
    {
        $this->ensureDirectory(dirname($path));
    }

    protected function getExistingPaths()
    {
        $paths = [];
        $dir = new \RecursiveDirectoryIterator($this->path, \FilesystemIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($dir);
        $pos = strlen($this->path . DIRECTORY_SEPARATOR);
        foreach ($files as $file => $obj) {
            $paths[] = substr($file, $pos);
        }
        return $paths;
    }

    protected function getFiles()
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__ . '/../../templates'));
        $files = [];
        foreach ($this->site->getTemplates() as $template) {
            if ($template->hasFixture()) {
                try {
                    $path = substr($template->getTemplateName(), 0, -5) . '.html';
                    $templatePath = 'templates/' . $path;
                    $templateName = $template->getTemplateName();

                    $fixture = $template->getFixture();
                    $example = $this->site->render($templateName, $fixture);
                    $vars = ['content' => $example];
                    $content = $twig->render('preview-content.twig', $vars);
                    $files[$templatePath] = new File($templatePath, $content);

                    $vars = ['path' => $templatePath];
                    $files[$path] = new File($path, $twig->render('preview.twig', $vars));

                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return $files;
    }

    protected function handleFile(FileInterface $file, $path)
    {
        $event = new FileEvent($path);
        $destination = $this->makePath($path);
        $fileTime = is_file($destination) ? filemtime($destination) : 0;
        if ($this->force || $fileTime < $file->getTime()) {
            $this->ensurePathDirectory($destination);
            $file->put($destination);
            $event->stop(FileEvent::UPDATED);
        } else {
            $event->stop(FileEvent::SKIPPED);
        }
        return $event;
    }

    protected function makePath($path)
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->normalizePath($path);
    }

    protected function normalizePath($path)
    {
        return strtr($path, '\\/', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
    }
}
