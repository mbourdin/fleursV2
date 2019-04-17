<?php
namespace App\Utility;
class Login
{   private $login;
    private $password;
    /**
     * Login constructor.
     */
    public function __construct()
    {   $this->login=null;
        $this->password=null;
    }
    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }
    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
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
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}