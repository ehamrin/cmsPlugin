<?php


namespace Form\model;


abstract class ElementFacade
{
    /*
     * @var $object \Form\model\IElement
     */
    protected $object;

    public function __construct($name, $value = ""){
        $class = $this->GetDevClassName();
        $object = new $class($name, $value);
        $this->object = $object;
    }

    public function SetTemplateName(\string $name){
        $this->object->SetTemplateName($name);
        return $this;
    }

    public function SetValue($value){
        $this->object->SetValue($value);
        return $this;
    }

    public function SetLabel(\string $label)
    {
        $this->object->SetLabel($label);
        return $this;
    }

    public function SetValidation(IValidation ...$validators){
        $this->object->SetValidation(...$validators);
        return $this;
    }
    public function SetAttributes(Option ...$attributes){
        $this->object->SetAttributes(...$attributes);
        return $this;
    }

    public function SetComparator(IComparator ...$comparators){
        $this->object->SetComparator(...$comparators);
        return $this;
    }

    public function GetModelObject() : IElement
    {
        return $this->object;
    }

    private function GetDevClassName(){
        $classArr = get_class($this);
        $classArr = explode('\\', $classArr);   // Explode namespace string
        $classname = array_pop($classArr);      // Remove class name from namespaces
        $classArr[] = "dev";                    // Add "dev" namespace at end of namespaces
        $classArr[] = $classname;               // Add classname back in array
        return implode('\\', $classArr);      // Rebuild namespace string
    }
}