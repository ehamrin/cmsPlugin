<?php


namespace plugin\Slider\controller;

use annotation\repository\PDORepository;
use plugin\Slider\model\Slide;

class PublicController extends \app\AbstractController
{

    private $repository;

    public function __construct(\Application $application, PDORepository $repository)
    {
        parent::__construct($application);
        $this->repository = $repository;
    }

    public function Widget(){
        $this->application->AddScriptDependency("/js/Slider/slide.js");
        $this->application->AddCSSDependency("/css/Slider/Slider.css");

        $slides = $this->repository->findAll();

        return $this->View('widgets.slider_widget', compact('slides'));
    }
}