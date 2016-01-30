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
 * @Version v1.0
 */

use app\AbstractPlugin;

class CookieBanner extends AbstractPlugin
{
    public function HookPageHeaderHTML(...$args){
        return $this->Init('Public', 'HeaderContent');
    }
}