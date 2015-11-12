<?php


namespace plugin\Logger\view;


use Form\model\InputDoesNotExistException;

class LoggerView extends \plugin\AbstractView
{
    public function __construct(\plugin\Logger\model\LoggerModel $model)
    {
        $this->model = $model;
    }

    public function ViewAllLogs(){
        return $this->View('AdminList', array('logs' => $this->model->fetchAllError()));

    }

    public function ViewAllVisitorSummary(){
        return $this->View('VisitorSummaryList', array('logs' => $this->model->fetchVisitorSummary()));

    }

    public function ViewAllVisitors(){
        return $this->View('VisitorList', array('logs' => $this->model->fetchAllVisitor()));

    }
}