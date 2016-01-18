<?php

namespace plugin\Slider\controller;

use annotation\repository\PDORepository;
use plugin\Slider\model\Slide;
use plugin\Slider\view\Slider;

class AdminController  extends \app\Admin\AbstractAdminController
{
    private $view;

    public function __construct(\Application $application, PDORepository $repository, Slider $view)
    {
        parent::__construct($application);
        $this->view = $view;
        $this->repository = $repository;
    }

    public function Index()
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");

        return $this->view->Index($this->repository->findAll());
    }

    public function Create()
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");
        $slide = new Slide();

        if($this->view->isPost() &&
            $slide->uploadFile()
        ){
            $slide->setName($_POST["name"]);
            if($this->repository->save($slide)) {
                $this->view->GoToIndex();
            }
        }

        return $this->view->Create($slide);
    }

    public function Edit($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");

        $slide = $this->repository->find($id);
        /* @var $slide Slide */

        if($slide == null){
            $this->view->GoToIndex();
        }

        if($this->view->isPost()
        ){
            if($this->view->hasFile()){
                $slide->uploadFile();
            }

            $slide->setName($_POST["name"]);
            if($this->repository->save($slide)) {
                $this->view->GoToIndex();
            }
        }

        return $this->view->Create($slide);
    }

    public function Delete($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");

        $slide = $this->repository->find($id);
        /* @var $slide Slide */

        if($slide == null){
            $this->view->GoToIndex();
        }

        $slide->removeFile();
        $this->repository->delete($slide);

        $this->view->GoToIndex();
    }
}