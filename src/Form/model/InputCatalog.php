<?php


namespace Form\model;

class ElementExistsException extends \Exception{}
class InputDoesNotExistException extends \Exception{}

class InputCatalog
{
    private $input = array();
    private $error = array();
    private $success = null;

    public function Add(IElement $toBeAdded){
        foreach($this->GetAll() as $input){
            if($toBeAdded->IsSame($input)){
                throw new ElementExistsException("Input with name " . $toBeAdded->GetName() . " already exist!");
            }
        }
        $this->input[$toBeAdded->GetName()] = $toBeAdded;
    }

    /**
     * @return IElement[]
     */
    public function GetAll(){
        return $this->input;
    }

    public function Get(\string $name) : IElement
    {
        if(!isset($this->input[$name])){
            throw new InputDoesNotExistException("The element you're looking for does not exist");
        }
        return $this->input[$name];
    }

    public function UpdateValues(array $data)
    {
        foreach($this->GetAll() as $input){
            $input->UpdateValue($data);
        }
    }

    public function IsValid() : \bool
    {
        $status = true;
        foreach($this->GetAll() as $input){
            $input->Validate($this);
            if(count($input->GetErrorMessage())){
                $status =  false;
            }
        }

        return $status;
    }

    public function Export()
    {
        $ret = array();

        foreach($this->GetAll() as $input){
            $value = $input->Export();
            if($value !== null) {
                $ret = $this->array_merge($ret, $value);
            }
        }

        return $ret;

    }

    /**
     * Version of array_merge_recursive without overwriting numeric keys
     *
     * @param  array $array1 Initial array to merge.
     * @param  array ...     Variable list of arrays to recursively merge.
     *
     * @link   https://gist.github.com/ptz0n/1646171
     * @author Martyniuk Vasyl <martyniuk.vasyl@gmail.com>
     */
    private function array_merge($base, ...$arrays) {
        foreach ($arrays as $array) {
            reset($base); //important
            while (list($key, $value) = @each($array)) {
                if (is_array($value) && @is_array($base[$key])) {
                    $base[$key] = $this->array_merge($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            }
        }

        return $base;
    }

    public function AddError(...$messages){
        $this->error = $messages;
    }

    public function AddSuccess($message){
        $this->success = $message;
    }

    public function GetSuccess(){
        return $this->success;
    }


    public function GetError(){
        return $this->error;
    }

    public function UpdateValue(\string $input, $value){
        if(!isset($this->input[$input])){
            throw new InputDoesNotExistException("The element you're looking for does not exist");
        }

        $object = $this->input[$input];
        /* @var $object \Form\model\Element */
        $object->SetValue($value);
    }

}