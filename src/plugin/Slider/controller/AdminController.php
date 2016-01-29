<?php

namespace plugin\Slider\controller;

use annotation\repository\PDORepository;
use app\Admin\AbstractAdminController;
use plugin\Slider\model\Slide;

class AdminController  extends AbstractAdminController
{
    public function __construct(\Application $application, PDORepository $repository)
    {
        parent::__construct($application);
        $this->repository = $repository;
    }

    public function Index()
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");
        $slides = $this->repository->findAll();

        return $this->View("admin.slider_index", compact('slides'));
    }

    public function Create()
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");
        $slide = new Slide();

        if($this->requestMethod() == 'POST' &&
            $slide->uploadFile()
        ){
            $slide->setName($_POST["name"]);
            $slide->setAlignment($_POST["alignment"]);
            if($this->repository->save($slide)) {
                $this->Redirect('/admin/slider');
            }
        }

        return $this->View("admin.slider_create", compact('slide'));
    }

    public function Edit($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");

        $slide = $this->repository->find($id) ?? $this->Redirect('/admin/slider');
        /* @var $slide Slide */

        if($this->requestMethod() == 'POST'){
            if($this->hasFile()){
                $slide->uploadFile();
            }

            $slide->setName($_POST["name"]);
            $slide->setAlignment($_POST["alignment"]);

            if($this->repository->save($slide)) {
                $this->Redirect('/admin/slider');
            }
        }

        return $this->View("admin.slider_edit", compact('slide'));
    }

    public function Delete($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-slider");

        $slide = $this->repository->find($id) ?? $this->Redirect('/admin/slider');
        /* @var $slide Slide */

        $slide->removeFile();
        $this->repository->delete($slide);

        $this->Redirect('/admin/slider');
    }
}