<?php


namespace app\Page\model;


class Page
{
    private $name;
    private $slug;
    private $content;
    private $project;
    private $template;
    private $module;
    private $id;

    /**
     * @param string $name
     */
    public function SetName($name)
    {
        $this->name = $name;
        $this->GenerateSlug();
    }

    /**
     * @param string $content
     */
    public function SetContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $project
     */
    public function SetProject($project)
    {
        $this->project = $project;
    }

    /**
     * @param int $id
     */
    public function SetID($id)
    {
        $this->id = $id;
    }


    public function GetName(){
        return $this->name;
    }

    public function GetSlug(){
        return $this->slug;
    }

    public function GetID(){
        return $this->id;
    }

    public function GetContent(){
        return $this->content;
    }

    public function GetProject(){
        return $this->project;
    }

    public function GetTemplate(){
        return $this->template ?? 'full-width';
    }

    public function SetTemplate($template){
        $this->template = $template;
    }

    public function GetModule(){
        return $this->module ?? '';
    }

    public function SetModule($module){
        $this->module = $module;
    }

    /* http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string */
    public function GenerateSlug(){
        if($this->GetID() == 1){
            return "";
        }

        $text = $this->GetName();

        $text = str_replace(array('å', 'Å', 'ä', 'Ä', 'ö', 'Ö'), array('a', 'A', 'a', 'A', 'o', 'O'), $text);

        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}