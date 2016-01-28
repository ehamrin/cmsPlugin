<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?= $page->GetName(); ?> - <?= $settings["page-site-title"]->GetValue(); ?></title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta name="description" content="<?= $page->GetName(); ?>">
    <meta name="author" content="Erik Hamrin">
    <meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1.0, user-scalable=false">

<?php foreach($this->application->GetCSSDependency() as $stylesheet): ?>
    <link rel="stylesheet" href="<?= $stylesheet; ?>">
<?php endforeach; ?>
</head>
<body>
    <?= getHTMLFlashMessage(); ?>
    <?= $headerHook; ?>
    <header>
        <div id="menu_open"></div>
        <nav>
            <?= $this->RenderNav($page); ?>
        </nav>
        <h1><?=$page->GetName(); ?></h1>
    </header>
    <main>
        <?php include('templates' . DIRECTORY_SEPARATOR . $page->GetTemplate() . '.php'); ?>
    </main>
<?php foreach($this->application->GetScriptDependency() as $script): ?>
    <script type="text/javascript" src="<?= $script; ?>"></script>
<?php endforeach; ?>
<?php if($this->application->PluginExists('Offline') && HTTPS): ?>
    <script type="text/javascript" src="/js/Offline/offline.min.js"></script>
<?php endif; ?>
</body>
</html>