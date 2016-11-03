<?php

namespace Outpost\Content\Patterns\Navigation\Pagination;

class Pager implements PagerInterface, \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $baseUrl;

    protected $pageCount;

    protected $page;

    public function __construct($baseUrl, $pageCount, $page = 1)
    {
        $this->baseUrl = $baseUrl;
        $this->pageCount = $pageCount;
        $this->page = $page;
    }

    public function count()
    {
        return $this->pageCount;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getPages());
    }

    public function getNext()
    {
        if (!$this->hasNext()) throw new \OutOfRangeException();
        return $this->getPage($this->page + 1);
    }

    public function getPage($number)
    {
        return $this->makePage($number);
    }

    public function getPageUrl($number)
    {
        return $this->makeUrlWithParameters($this->baseUrl, ['page' => $number]);
    }

    public function getPages()
    {
        return $this->makePages();
    }

    public function getPrevious()
    {
        if (!$this->hasPrevious()) throw new \OutOfRangeException();
        return $this->getPage($this->page - 1);
    }

    public function hasNext()
    {
        return $this->offsetExists($this->page + 1);
    }

    public function hasPrevious()
    {
        return $this->offsetExists($this->page - 1);
    }

    public function offsetExists($number)
    {
        $number = (int)$number;
        return $number > 0 && $number <= $this->count();
    }

    public function offsetGet($number)
    {
        if (!$this->offsetExists($number)) {
            throw new \OutOfRangeException("Unknown page: $number");
        }
        return $this->makePage((int)$number);
    }

    public function offsetSet($number, $page)
    {
        throw new \BadMethodCallException();
    }

    public function offsetUnset($number)
    {
        throw new \BadMethodCallException();
    }

    protected function makePage($number)
    {
        return new Page($number, $this->getPageUrl($number), $number == $this->page);
    }

    protected function makePages()
    {
        $pages = [];
        foreach (range(1, $this->pageCount) as $number) {
            $pages[$number] = $this->makePage($number);
        }
        return $pages;
    }

    protected function makeQueryString($query)
    {
        return !is_string($query) ? http_build_query($query) : $query;
    }

    protected function makeUrlWithParameters($url, array $params)
    {
        $query = [];
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }
        $parts['query'] = array_replace_recursive($query, $params);
        return $this->makeUrl($parts);
    }

    protected function makeUrl(array $parts)
    {
        $url = '';
        if (!empty($parts['scheme'])) {
            $url .= $parts['scheme'] . '://';
        }
        if (!empty($parts['host'])) {
            $url .= $parts['host'];
        }
        if (!empty($parts['port'])) {
            $url .= ':' . $parts['port'];
        }
        if (!empty($parts['user']) || !empty($parts['pass'])) {
            $auth = implode(':', array_filter([$parts['user'], $parts['pass']]));
            $url .= $auth . '@';
        }
        if (!empty($parts['path'])) {
            $url .= $parts['path'];
        }
        if (!empty($parts['query'])) {
            $url .= '?' . $this->makeQueryString($parts['query']);
        }
        if (!empty($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }
        return $url;
    }
}
