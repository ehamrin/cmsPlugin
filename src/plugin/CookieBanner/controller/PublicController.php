<?php


namespace plugin\CookieBanner\controller;


class PublicController extends \app\AbstractController
{
    public function HeaderContent()
    {
        if(!isset($_COOKIE["cookie_approval"]) && !$this->userAddedCookie()) {
            $this->application->AddScriptDependency("/js/CookieBanner/script.js");
            $this->application->AddCSSDependency("/css/CookieBanner/stylesheet.css");
            return $this->View('cookie_header');
        }
        return '';
    }

    private function userAddedCookie(){
        if(isset($_GET["cookie_approval"])){
            setcookie("cookie_approval", true, 2000000000);
            return true;
        }
        return false;
    }

}