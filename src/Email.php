<?php


namespace nickdnk\ZeroBounce;

class Email
{

    private $email, $ipAddress;

    public function __construct(string $email, ?string $ipAddress = null)
    {

        $this->email = $email;
        $this->ipAddress = $ipAddress;
    }

    public function getEmail(): string
    {

        return $this->email;
    }

    public function getIpAddress(): ?string
    {

        return $this->ipAddress;
    }

}
