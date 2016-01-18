<?php


namespace app\Logger\view;


use app\Logger\model\LoggerModel;
use Form\model\InputDoesNotExistException;

class LoggerView extends \app\AbstractView
{
    private $model;
    public function __construct(LoggerModel $model)
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