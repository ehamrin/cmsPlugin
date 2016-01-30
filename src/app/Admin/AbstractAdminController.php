<?php

namespace app\Admin;


abstract class AbstractAdminController extends \app\AbstractController
{

    protected function AuthorizeOrGoToAdmin($permission){
        if(!$this->Authorize($permission)){
            $this->Redirect('/admin');
        }
    }
}