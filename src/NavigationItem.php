<?php


class NavigationItem
{
    private $title;
    private $link;
    private $subs;
    private $icon;

    public function __construct($title, $link, $subs = array(), \string $permission = '', $icon = ''){
        $this->title = $title;
        $this->link = $link;
        $this->subs = $subs;
        $this->permission = $permission;
        $this->icon = $icon;
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

    public function GetIcon(){
        return $this->icon;
    }

    public function HasPermission(){
        return $this->permission != '';
    }

    public function HasSubs(){
        return count($this->subs) > 0;
    }
}