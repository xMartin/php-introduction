<?php

class Address
{
    protected $street;
    protected $house_number;
    protected $city;
    protected $postal_code;
    protected $country;

    public function __construct($street, $house_number, $city, $postal_code, $country)
    {
        $this->street = $street;
        $this->house_number = $house_number;
        $this->city = $city;
        $this->postal_code = $postal_code;
        $this->country = $country;
    }

    public function __toString()
    {
        return $this->street . PHP_EOL . $this->postal_code . ', ' . $this->city . PHP_EOL . $this->country;
    }
}

$my_address = new Address("Main Street", 42, "Some Town", 12345, "Far Far Away");

echo $my_address .  PHP_EOL;
