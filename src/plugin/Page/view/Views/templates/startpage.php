        <div class="wrapper">
            <div id="inner_content">
                <?= $page->GetContent(); ?>
            </div>
        </div>
<?php if($widget = $this->application->GetWidget('ContactForm')): ?>
        <div class="full-widget">
            <?= $widget->DoWidget('Startpage') ?>
        </div>
<?php endif; ?>
