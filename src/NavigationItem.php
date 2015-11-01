<?php


class NavigationItem
{
    private $title;
    private $link;
    private $subs;

    public function __construct($title, $link, $subs = array(), \string $permission = ''){
        $this->title = $title;
        $this->link = $link;
        $this->subs = $subs;
        $this->permission = $permission;
    }

    public function GetTitle(){
        return $this->title;
    }

    public function GetLink(){
        return $this->link;
    }

    public function GetSubs(){
        return $this->subs;
    }

    public function GetPermission(){
        return $this->permission;
    }

    public function HasPermission(){
        return $this->permission != '';
    }

    public function HasSubs(){
        return count($this->subs) > 0;
    }
}