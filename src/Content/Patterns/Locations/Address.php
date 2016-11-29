<?php

namespace Outpost\Content\Patterns\Locations;

class Address implements AddressInterface, \JsonSerializable
{

    /**
     * @var CityInterface
     */
    protected $city;

    /**
     * @var string
     */
    protected $street;

    public function __construct(array $source = null)
    {
        if (isset($source)) {
            foreach ($source as $key => $value) {
                switch ($key) {
                    case 'city':
                        $this->setCity(new City($value));
                        break;
                    case 'street':
                        $this->setStreet($value);
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
        return (string)$this->getStreet();
    }

    /**
     * @return CityInterface
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'city' => $this->getCity(),
            'street' => $this->getStreet(),
        ];
    }

    /**
     * @param CityInterface $city
     */
    public function setCity(CityInterface $city)
    {
        $this->city = $city;
    }

    /**
     * @param $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }
}
