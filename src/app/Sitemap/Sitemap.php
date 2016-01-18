<?php


namespace plugin\Sitemap;


class Sitemap extends \app\AbstractPlugin
{
    private $file;

    public function __construct(\Application $application)
    {
        parent::__construct($application);
        $this->file = APP_ROOT . 'public' . DIRECTORY_SEPARATOR . 'sitemap.xml';

    }

    public function Install()
    {
        try{
            fopen($this->file, "w");
            $this->application->InvokeEvent('GenerateNewSitemap');
        }catch (\Throwable $e){
            throw new \Exception('Cannot create sitemap.xml: ' . $e->getMessage());
        }
    }

    public function Uninstall()
    {
            unlink($this->file);
    }

    public function IsInstalled()
    {
        return file_exists($this->file);
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookGenerateNewSitemap(){

        $data = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach($this->application->InvokeEvent("GenerateSitemap") as $event){
            $data .= $event->GetData();
        }

        $data .= '</urlset>';
        $handle = fopen($this->file, 'w');
        fwrite($handle, $data);
    }
}