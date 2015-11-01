<?php


namespace Form\model;

class AttributeNotAllowedException extends \Exception {}

abstract class Element implements IElement
{
    protected $name;
    protected $label;
    protected $value;
    protected $template;
    protected $validator = array();
    protected $attribute = array();
    protected $comparator = array();
    protected $error = array();

    public function __construct($name, $value = "")
    {
        $this->name = $name;
        $this->value = $value;
        $this->template = "";
    }

    public function IsSame(IElement $element)
    {
        return $this->name == $element->GetName();
    }

    public function GetName(){
        return $this->name;
    }

    public function SetTemplateName(\string $name)
    {
        $this->template = $name;
    }

    public function GetTemplateName() : \string
    {
        return $this->template;
    }

    public function GetValue()
    {
        return $this->value;
    }

    public function SetValue($value)
    {
        $this->value = $value;
    }

    public function SetAttributes(Option ...$options)
    {
        foreach($options as $option){
            /* @var $option Option */
            switch($option->GetName()){
                case 'name':
                case 'id':
                case 'type':
                case 'value':
                    throw new AttributeNotAllowedException('The attribute "' . $option->GetName() . '" is not allowed since it is most likely being used by the view file');
                    break;
                default:
                    $this->attribute[] = $option;
                    break;

            }
        }
    }

    /**
     * @return Option[]
     */
    public function GetAttributes()
    {
        return $this->attribute;
    }

    public function SetLabel(\string $label)
    {
        $this->label = $label;
    }

    public function GetLabel()
    {
        return $this->label;
    }

    public function SetValidation(IValidation ...$validators)
    {
        $this->validator = $validators;
    }

    /**
     * @return IValidation[]
     */
    private function GetValidators()
    {
        return $this->validator;
    }

    public function SetComparator(IComparator ...$comparators){
        $this->comparator = $comparators;
    }

    /**
     * @return IComparator[]
     */
    private function GetComparator(){
        return $this->comparator;
    }


    public function Validate(InputCatalog $catalog){
        $valid = true;

        foreach($this->GetValidators() as $key => $validator){
            if($validator->Validate($this->value) == FALSE){
                $valid = false;
                $this->AddError($validator->GetMessage(), $key);

            }
        }

        foreach($this->GetComparator() as $key => $comparator){
            $key += count($this->GetValidators()); //Offset index in array by number of validators

            $toCompare = $catalog->Get($comparator->GetName());
            if($comparator->Validate($this->value, $toCompare->GetValue()) == FALSE){
                $valid = false;
                $this->AddError($comparator->GetMessage(), $key);
            }
        }
        return $valid;
    }

    public function AddError(\string $message, \string $key = null){
        if($key == null){
            $key = count($this->error);
        }
        $this->error[$key] = $message;
    }

    public function GetErrorMessage(){
       return $this->error;
    }

    public function GetClassName(){
        $class = get_class($this);
        $array = explode('\\', $class);
        return array_pop($array);
    }

    public function Export(){
        $name = str_replace(']', '', $this->name);
        $name = explode('[',$name);
        $name = array_reverse($name, true);

        $array = $this->GetValue();
        foreach($name as $index){
            $array = array($index => $array);
        }
        return $array;
    }

    public function UpdateValue(array $data){
        $name = str_replace(']', '', $this->name);
        $name = explode('[',$name);

        foreach($name as $index){
            if(isset($data[$index])){
                $data = $data[$index];
            }
        }

        $this->value = !is_array($data) ? $data : '';
    }
}