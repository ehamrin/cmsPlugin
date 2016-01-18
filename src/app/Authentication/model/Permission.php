<?php


namespace app\Authentication\model;


class Permission
{
    private $permission;
    private $name;

    public function __construct(\string $name, \string $permission){
        $this->name = $name;
        $this->permission = $permission;

    }

    /**
     * @return string
     */
    public function GetPermission()
    {
        return $this->permission;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

}