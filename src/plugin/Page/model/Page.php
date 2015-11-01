<?php


namespace plugin\Page\model;


class Page
{
    private $name;
    private $slug;
    private $content;
    private $project;
    private $id;

    public function __construct($name, $slug, $content, $id = 0, $project = null){
        $this->name = $name;
        if($this->GetID() == 1){
            $this->slug = "";
        }else{
            $this->slug = $slug;
        }

        $this->content = $content;
        $this->project = $project;
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

    /* http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string */
    public function GenerateSlug(){
        if($this->GetID() == 1){
            return "";
        }

        $text = $this->GetName();

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