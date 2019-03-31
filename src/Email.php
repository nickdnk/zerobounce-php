<?php


namespace nickdnk\ZeroBounce;

class Email
{

    private $email, $ipAddress;

    /**
     * Email constructor.
     *
     * @param $email
     * @param $ipAddress
     */
    public function __construct(string $email, ?string $ipAddress = null)
    {

        $this->email = $email;
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {

        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getIpAddress(): ?string
    {

        return $this->ipAddress;
    }


}