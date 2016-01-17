<?php


namespace plugin\Logger\controller;


class AdminController
{
    public function __construct($application, $model, $view)
    {
        $this->application = $application;
        $this->model = $model;
        $this->view = $view;
    }

    public function Index(){
        return $this->Error();
    }

    public function Error(){
        return $this->view->ViewAllLogs();
    }

    public function Visitor(){
        //return $this->view->ViewAllVisitors();
        return $this->view->ViewAllVisitorSummary();
    }

    public function VisitorVerbose(){
        //return $this->view->ViewAllVisitors();
        return $this->view->ViewAllVisitors();
    }

}