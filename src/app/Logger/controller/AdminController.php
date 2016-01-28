<?php


namespace app\Logger\controller;


use app\Logger\view\LoggerView;

class AdminController extends \app\Admin\AbstractAdminController
{
    public function __construct($application, $model, LoggerView $view)
    {
        parent::__construct($application);
        $this->model = $model;
        $this->view = $view;
    }

    public function Index(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        return $this->Error();
    }

    public function Error(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        return $this->view->ViewAllLogs();
    }

    public function Visitor(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        return $this->view->ViewAllVisitorSummary();
    }

    public function VisitorVerbose(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        return $this->view->ViewAllVisitors();
    }

}