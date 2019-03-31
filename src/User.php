<?php


namespace nickdnk\ZeroBounce;

class User
{

    private $firstName, $lastName, $gender, $city, $region, $zipCode, $country;

    /**
     * User constructor.
     *
     * @param $firstName
     * @param $lastName
     * @param $gender
     * @param $city
     * @param $region
     * @param $zipCode
     * @param $country
     */
    public function __construct($firstName, $lastName, $gender, $city, $region, $zipCode, $country)
    {

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->city = $city;
        $this->region = $region;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {

        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {

        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {

        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {

        return $this->region;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {

        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {

        return $this->country;
    }


}