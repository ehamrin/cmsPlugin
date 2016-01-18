<?php

namespace app\Logger;

/**
 * @Name Error log
 * @Description Log any errors that occur runtime
 * @Author Erik Hamrin
 * @Version v0.5
 */

class Logger extends \app\AbstractPlugin
{
    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\LoggerModel();
        $this->view = new view\LoggerView($this->model);
        $this->AdminController = new controller\AdminController($this->application, $this->model, $this->view);

    }

    public function Install(){
        $this->model->Install();
    }

    public function UnInstall(){
        $this->model->Uninstall();
    }

    public function IsInstalled(){
        return $this->model->IsInstalled();
    }


    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookAdminItems(){

        return array(
            new \NavigationItem(
                'Logger',
                'log',
                array(
                    new \NavigationItem(
                        'Errors',
                        'log/error',
                        array(),
                        'view-error-log',
                        'fa-database'
                    ),
                    new \NavigationItem(
                        'Visitors',
                        'log/visitor',
                        array(),
                        'view-error-log',
                        'fa-database'
                    ),
                    new \NavigationItem(
                        'Visitors Extended',
                        'log/visitor-verbose',
                        array(),
                        'view-error-log',
                        'fa-database'
                    )),
                'view-error-log',
                'fa-database'
            )
        );
    }

    public function HookUserPermissions(){
        return array(new \app\Authentication\model\Permission('View error log', 'view-error-log'));
    }

    public function HookUncaughtError(\Exception $e){
        $this->model->logException($e);
    }

    public function HookUncaughtDBError(\Exception $e){
        $this->model->logException($e);
        throw $e;

    }

    public function HookNewVisitor(){
        $user = null;

        if($this->application->IsAuthenticated()){
            $user = $this->application->GetUser()->GetUsername();
        }

        $this->model->logVisitor($user);
    }
}