<?php

namespace App\Http\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class SubscriberData extends DataTransferObject
{

    public int $id;
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $username;
    /**
     * @var string
     */
    public string $phone;
    public array $address;
    public string $website;
    public array $company;


}

