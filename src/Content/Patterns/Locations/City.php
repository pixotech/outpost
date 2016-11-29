<?php

namespace Outpost\Content\Patterns\Locations;

class City implements CityInterface, \JsonSerializable {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var StateInterface
     */
    protected $state;

    /**
     * @param array $source
     */
    public function __construct(array $source = null) {
        if (isset($source)) {
            foreach ($source as $key => $value) {
                switch ($key) {
                    case 'name':
                        $this->setName($value);
                        break;
                    case 'state':
                        $this->setState(new State($value));
                        break;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return StateInterface
     */
    public function getState()
    {
        return $this->state;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'state' => $this->getState(),
        ];
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param StateInterface $state
     */
    public function setState(StateInterface $state)
    {
        $this->state = $state;
    }
}
