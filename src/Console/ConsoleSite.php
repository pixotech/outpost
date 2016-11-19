<?php

namespace Outpost\Console;

use Outpost\Files\Directory;
use Outpost\Site;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class ConsoleSite extends Site
{
    /**
     * @var string
     */
    protected $publicPath;

    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    public function getTemplatePreview()
    {
        $templates = $this->site->getTemplates();
        if (!empty($_GET['template'])) {
            if (isset($templates[$_GET['template']])) {
                $template = $templates[$_GET['template']];
                $vars = [];
                if ($template->hasFixture()) {
                    $vars = $template->getFixture();
                }
                $preview = $this->site->render($template->getTemplateName(), $vars);
                if (!empty($templates['_outpost/preview.twig'])) {
                    $pym = $this->getPym();
                    $script = $this->render("console/templates/preview-script.twig", ['pym' => $pym]);
                    $preview = $this->site->render("_outpost/preview.twig", ['preview' => $preview, 'script' => $script]);
                }
                print $preview;
                return;
            }
        }
        header("HTTP/1.0 404 Not Found");
        print "Not found";
    }

    public function getTemplatesIndex()
    {
        $templates = $this->site->getTemplates();
        $vars = [
            'stylesheet' => $this->getStylesheet(),
            'script' => $this->getScript(),
        ];
        if (!empty($_GET['template'])) {
            if (isset($templates[$_GET['template']])) {
                $vars['template'] = $templates[$_GET['template']];
                print $this->render("console/templates/template.twig", $vars);
            } else {
                header("HTTP/1.0 404 Not Found");
                print "Not found";
            }
        } else {
            $vars['templates'] = $templates;
            print $this->render("console/templates/index.twig", $vars);
        }
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return $this->publicPath;
    }

    public function respond(Request $request)
    {
        $requestPath = $request->getPathInfo();
        if (!empty($this->publicPath)) {
            $path = $this->publicPath . DIRECTORY_SEPARATOR . ltrim($requestPath, '/');
            if (is_file($path)) {
                $extension = null;
                if (false !== $pos = strrpos(basename($path), '.')) {
                    $extension = substr(basename($path), $pos + 1);
                }
                switch ($extension) {
                    case 'css':
                        $mimeType = 'text/css';
                        break;
                    case 'js':
                        $mimeType = 'text/javascript';
                        break;
                    default:
                        $mimeType = mime_content_type($path);
                }
                if (isset($mimeType)) {
                    header("Content-Type: $mimeType");
                }
                readfile($path);
                return null;
            }
        }
        if ($requestPath == '/favicon.ico') {
            return null;
        }
        return parent::respond($request);
    }

    /**
     * @param string $path
     */
    public function setPublicPath($path)
    {
        $this->publicPath = $path;
    }

    protected function getPym()
    {
        return file_get_contents(__DIR__ . '/../../assets/pym/pym.js');
    }

    protected function getScript()
    {
        return $this->getPym();
    }

    protected function getStylesheet()
    {
        return file_get_contents(__DIR__ . '/../../assets/outpost.css');
    }

    protected function getTemplatesDirectory()
    {
        return new Directory($this->getTemplatesPath());
    }

    protected function getTemplatesPath()
    {
        return __DIR__ . '/../../templates';
    }

    protected function makeRouter()
    {
        $router = parent::makeRouter();
        $router->route('GET', '_outpost/templates', [$this, 'getTemplatesIndex']);
        $router->route('GET', '_outpost/templates/preview', [$this, 'getTemplatePreview']);
        return $router;
    }

    protected function makeTwigLoader()
    {
        return new \Twig_Loader_Filesystem($this->getTemplatesPath());
    }
}
