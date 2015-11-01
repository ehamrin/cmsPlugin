<?php


namespace plugin\Settings\view;
use \plugin\Settings\model;

class Setting
{
    private $form;
    private $model;
    private $application;

    public function __construct(\Application $application, model\SettingModel $model){
        $this->application = $application;
        $this->model = $model;
        $this->form = $this->GenerateForm();
    }

    private function GenerateForm(){
        $form = new \Form\controller\FormController("SettingsForm");
        foreach($this->model->GetAll() as $setting){
            $form->AddInput(
                (new \Form\model\input\Text($setting->GetName(), $setting->GetValue()))
                    ->SetLabel($setting->GetDescription())
            );
        }
        $form->AddInput(new \Form\model\input\Submit("Send", "Submit changes"));
        return $form;
    }

    public function ViewSettings(){
        return $this->form->GetView();
    }

    public function WasSubmitted(){
        return $this->form->WasSubmitted();
    }

    public function GetSettings(){
        $ret = array();
        foreach($this->form->GetData() as $key => $value){
            $ret[] = new model\Setting($key, $value);
        }
        return $ret;
    }

    public function EditSuccess(){
        $this->form->InjectFormSuccess('<a class="confirmed-add" href="/admin/setting">You successfully saved the settings!</a>');
    }
}