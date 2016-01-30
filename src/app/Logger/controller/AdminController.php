<?php


namespace app\Logger\controller;


use app\Logger\model\LoggerModel;
use app\Logger\view\LoggerView;

class AdminController extends \app\Admin\AbstractAdminController
{
    public function __construct($application, LoggerModel $model, LoggerView $view)
    {
        parent::__construct($application);
        $this->model = $model;
        $this->view = $view;
    }

    public function Index(){
        return $this->Error();
    }

    public function Error(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        $logs = $this->model->fetchAllError();
        return $this->View('admin.logger_error', compact('logs'));
    }

    public function Visitor(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        $logs = $this->model->fetchVisitorSummary();
        return $this->View('admin.logger_visitor', compact('logs'));
    }

    public function VisitorVerbose(){
        $this->AuthorizeOrGoToAdmin("view-error-log");
        $logs = $this->model->fetchAllVisitor();
        return $this->View('admin.logger_visitor_verbose', compact('logs'));
    }

}