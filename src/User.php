<?php


namespace nickdnk\ZeroBounce;

class User
{

    private $firstName, $lastName, $gender, $city, $region, $zipCode, $country;

    public function __construct(?string $firstName, ?string $lastName, ?string $gender, ?string $city, ?string $region, ?string $zipCode, ?string $country)
    {

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->city = $city;
        $this->region = $region;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }

    public function getFirstName(): ?string
    {

        return $this->firstName;
    }

    public function getLastName(): ?string
    {

        return $this->lastName;
    }

    public function getGender(): ?string
    {

        return $this->gender;
    }

    public function getCity(): ?string
    {

        return $this->city;
    }

    public function getRegion(): ?string
    {

        return $this->region;
    }

    public function getZipCode(): ?string
    {

        return $this->zipCode;
    }

    public function getCountry(): ?string
    {

        return $this->country;
    }


}
