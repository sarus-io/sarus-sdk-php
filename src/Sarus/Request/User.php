<?php

namespace Sarus\Request\User;

class User
{
    private $email;
    private $firstName;
    private $lastName;
    private $identityProviderId;

    private $address1;
    private $address2;
    private $city;
    private $region;

    private $country;
    private $postalCode;

    /**
     * Required parameters for user request
     * @param $email
     * @param $firstName
     * @param $lastName
     * @param $identityProviderId
     */
    public function __construct($email, $firstName, $lastName, $identityProviderId)
    {
        $this->email = (string) $email;
        $this->firstName = (string) $firstName;
        $this->lastName = (string) $lastName;
        $this->identityProviderId = (int) $identityProviderId;
    }

    /**
     * Optional Parameter
     * @param string $address1
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->address1 = (string) $address1;
        return $this;
    }

    /**
     * Optional Parameter
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = (string) $address2;
        return $this;
    }

    /**
     * Optional Parameter
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = (string) $city;
        return $this;
    }

    /**
     * Optional Parameter
     * @param string $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = (string) $region;
        return $this;
    }

    /**
     * Optional Parameter
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = (string) $country;
        return $this;
    }

    /**
     * Optional Parameter
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = (string) $postalCode;
        return $this;
    }

    /**
     * @return array
     */
    public function toRequestArray()
    {
        $data =  [
            'email'      => $this->email,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'identity_provider_id' => $this->identityProviderId,

            'address1' => $this->address1,
            'address2' => $this->address2,
            'city_locality' => $this->city,
            'state_region' => $this->region,

            'postal_code' => $this->postalCode,
            'country' => $this->country
        ];

        return array_filter($data);
    }
}
