<?php

namespace app\Admin;


abstract class AbstractAdminController
{
    protected $application;

    public function __construct(\Application $application)
    {
        $this->application = $application;
    }

    protected function Verify($permission){
        return $this->application->IsAuthenticated() && $this->application->GetUser()->Can($permission);
    }

    protected function Authorize($permission){
        $verified = $this->Verify($permission);
        if(!$verified){
            header('HTTP/1.0 403 Forbidden');
        }

        return $verified;
    }

    protected function AuthorizeOrGoToAdmin($permission){
        if(!$this->Authorize($permission)){
            header("Location: /admin");
            exit;
        }
    }
}