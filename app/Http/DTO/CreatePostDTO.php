<?php

namespace App\Http\DTO;
use Spatie\DataTransferObject\DataTransferObject;
use DateTime;

class CreatePostDTO
{
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $username;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $phone;


    public function __construct($name, $username, $email, $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->username = $username;
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }


}
