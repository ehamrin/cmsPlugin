<?php

namespace app\Admin\view;

class Admin
{
    /* @var $application \Application */
    private $application;

    public function __construct(\Application $application){
        $this->application = $application;
    }

    public function SetApplication(\Application $application){
        $this->application = $application;
    }

    private function GetNavItems(){
        if($this->application->IsAuthenticated()){
            $items = array();

            foreach ($this->application->InvokeEvent("AdminItems") as $event) {
                /* @var $event \Event */
                foreach ($event->GetData() as $navitem) {
                    /* @var $navitem \NavigationItem */
                    if($this->application->GetUser()->Can($navitem->GetPermission())){
                        $items[] = $navitem;
                    }
                }
            }

            $html = '<li><a href="/admin/"><i class="fa fa-home"></i>&nbsp Admin panel</a></li>';

            foreach($items as $navitem){
                $html .= '<li>
                                <a href="/admin/' . $navitem->GetLink() . '">' . (!empty($navitem->GetIcon()) ? '<i class="fa ' . $navitem->GetIcon() . '"></i>&nbsp ' : '') . $navitem->GetTitle() . '</a>';
                if($navitem->HasSubs()){
                    $html .= '<ul>';
                    foreach($navitem->GetSubs() as $sub){
                        $html .= '<li><a href="/admin/' . $sub->GetLink() . '">' . $sub->GetTitle() . '</a></li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }

            $html .= '<li><a href="/admin/logout" class="logout-button"><i class="fa fa-power-off"></i>&nbsp;&nbsp; Logout</a></li>';

            return $html;
        }
        return null;
    }

    public function Render($body){
        return <<<HTML
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/vendors/sweetalert.css">
    <link rel="stylesheet" href="/css/admin.css?v=1.0">
    <script src="/scripts/jquery.js"></script>
    <script src="/vendors/sweetalert.min.js"></script>
    <script src="/vendors/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/vendors/fancybox/jquery.fancybox.pack.js"></script>
    <script src="/scripts/admin.js"></script>
    <script src="/scripts/scripts.js"></script>
    <script>
        tinymce.init({
            height: 500,
            selector:'.tinyMCE',
            valid_elements : '*[*]',
            plugins: [
                "advlist autolink lists link image charmap preview hr",
                "code responsivefilemanager",
                "fontawesome noneditable",
                "insertdatetime media nonbreaking contextmenu table contextmenu",
                "colorpicker imagetools importcss"
            ],
            extended_valid_elements: 'span[class]',
            content_css: [
                'https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
                '/css/normalize.css',
                '/css/tiny.css'
            ],
            importcss_append: true,
            image_advtab: true,
            external_filemanager_path:"/vendors/filemanager/",
            filemanager_title:"Filemanager" ,
            external_plugins: { "filemanager" : "/vendors/filemanager/plugin.min.js"},
            removed_menuitems: 'newdocument print',
            toolbar: "undo redo | bold italic underline | responsivefilemanager | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontawesome styleselect | forecolor backcolor | link unlink anchor | image media | preview code"
        });
    </script>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <?= getHTMLFlashMessage(); ?>
    <nav>
        <ul>
            {$this->GetNavItems()}
        </ul>
    </nav>
    <header><h1>Welcome to the admin area</h1></header>
    <main>
        <div class="wrapper">
            {$body}
        </div>
    </main>


</body>
</html>
HTML;

    }
    public function AdminPanel(){

        $ret = '<h1>You are logged in as ' . $this->application->GetUser()->GetUsername() . '</h1><!--';

        foreach ($this->application->InvokeEvent("AdminPanel") as $event) {

            /* @var $event \Event */
            if(!empty($event->GetData())){
            $ret .= '--><div class="admin-panel-item inline-1-3">' . $event->GetData() . '</div><!--';
            }
        }
        $ret .= '-->';

        return $ret;
    }

    public function GoToIndex(){
        header('Location: /admin');
        die();
    }
}