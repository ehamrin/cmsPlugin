<div class="wrapper">
    <div id="inner_content" class="inline-3-4">
    <?= $page->GetContent(); ?>
    </div>
    <div id="sidebar" class="inline-1-4">
<?php
        if($widget = $this->application->GetWidget('ContactForm')){ echo $widget->DoWidget('Top'); }
        if($widget = $this->application->GetWidget('NonExistent')){ echo $widget->DoWidget('Middle'); }
        if($widget = $this->application->GetWidget('NonExistent')){ echo $widget->DoWidget('Bottom'); }
?>
    </div>
</div>