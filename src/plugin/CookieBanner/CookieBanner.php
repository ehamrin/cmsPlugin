<?php
/**
 * Created by PhpStorm.
 * User: erikh_000
 * Date: 2016-01-18
 * Time: 20:50
 */

namespace plugin\CookieBanner;

/**
 * @Name Cookie Banner
 * @Description Add a cookie disclaimer
 * @Author Erik Hamrin
 * @Icon  fa-bell
 */

use app\AbstractPlugin;

class CookieBanner extends AbstractPlugin
{
    public function HookPageHeaderHTML(...$args){
        if(!isset($_COOKIE["cookie_approval"]) && !$this->userAddedCookie()){
            $this->application->AddScriptDependency("/js/CookieBanner/script.js");
            $this->application->AddCSSDependency("/css/CookieBanner/stylesheet.css");

            return <<<HTML
            <div id="cookie_banner">
                <div class="wrapper">
                <p class="disclaimer">
                    Vi använder cookies för att din upplevelse av webbplatsen ska bli bättre.
                    Genom att fortsätta använda vår webbplats accepterar du våra cookies.
                    <a target="_blank" href="http://sv.wikipedia.org/wiki/Webbkaka">Läs mer om cookies.</a>

                </p>

                <a href="?cookie_approval" class="button">Jag förstår</a>
                </div>
            </div>
HTML;

        }

        return "";
    }

    private function userAddedCookie(){
        if(isset($_GET["cookie_approval"])){
            setcookie("cookie_approval", true, 2000000000);
            return true;
        }
        return false;
    }
}