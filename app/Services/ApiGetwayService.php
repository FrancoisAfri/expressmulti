<?php

namespace App\Services;

class ApiGetwayService
{
    /**
     * @var
     */
    private $base_url;
    /**
     * @var
     */
    private $token;

    /*
     *
     */
    private $user_name;

    /**
     * @var
     */
    private $password;

    /**
     * @param $base_url
     * @param $token
     * @param $user_name
     * @param $password
     */


    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param mixed $base_url
     * @return ApiGetwayService
     */
    public function setBaseUrl(string $base_url): ApiGetwayService
    {
        $this->base_url = $base_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return ApiGetwayService
     */
    public function setToken(string $token): ApiGetwayService
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     * @return ApiGetwayService
     */
    public function setUserName(string $user_name): ApiGetwayService
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return ApiGetwayService
     */
    public function setPassword(string $password): ApiGetwayService
    {
        $this->password = $password;
        return $this;
    }

}
