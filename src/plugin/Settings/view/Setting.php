<?php


namespace plugin\Settings\view;
use plugin\AbstractView;
use \plugin\Settings\model;

class Setting extends AbstractView
{
    private $form;
    private $model;
    private $application;
    private $message = null;

    public function __construct(\Application $application, model\SettingModel $model){
        $this->application = $application;
        $this->model = $model;
    }

    public function ViewSettings($plugins){
        $html = '';

        foreach($plugins as $plugin => $event){
            $html .= $this->View('SettingInput', array(
                'plugin' => $plugin,
                'settings' => $event->getData(),
                'model' => $this->model
            ));
        }

        return $this->View('SettingForm', array(
            'message' => $this->message,
            'html' => $html
        ));
    }

    public function WasSubmitted(){
        return isset($_POST['submit']);
    }

    public function GetSettings(){
        $ret = array();
        foreach($_POST['settings'] as $key => $value){
            $ret[] = new model\Setting($key, $value);
        }
        return $ret;
    }

    public function EditSuccess(){
        $this->message = '<a class="confirmed-add" href="/admin/setting">You successfully saved the settings!</a>';
    }
}