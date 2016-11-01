<?php

namespace Outpost\Content\Documentor;

class Index implements IndexInterface, \Countable, \IteratorAggregate
{
    protected $index = [];

    public function __construct(DocumentorInterface $docs)
    {
        $index = [];
        foreach ($docs->getEntities() as $entity) {
            $names = $this->splitClassName($entity->getClassName());
            $index[$names[0]][] = $entity;
        }
        ksort($index);
        foreach ($index as $name => $entities) {
            if (count($entities) > 1) {
                /** @var EntityInterface $entity */
                foreach ($entities as $entity) {
                    $names = $this->splitClassName($entity->getClassName());
                    $name = array_shift($names);
                    do {
                        $paren = array_shift($names);
                    }
                    while ($paren && $paren == $name);
                    if ($paren) {
                        $name .= " ($paren)";
                    }
                    $this->index[$name] = $entity;
                }
            } else {
                $this->index[$name] = $entities[0];
            }
        }
    }

    public function count()
    {
        return count($this->index);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->index);
    }

    protected function splitClassName($className)
    {
        return array_map([Documentor::class, 'camelCaseToWords'], array_reverse(explode('\\', $className)));
    }
}
