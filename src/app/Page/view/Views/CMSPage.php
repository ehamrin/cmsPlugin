<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?= $page->GetName(); ?></title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta name="description" content="<?= $page->GetName(); ?>">
    <meta name="author" content="Erik Hamrin">
    <meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1.0, user-scalable=false">
    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="/vendors/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" href="/vendors/sweetalert.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.min.css?v=1.1">
<?php foreach($this->application->GetCSSDependency() as $stylesheet): ?>
    <link rel="stylesheet" href="<?= $stylesheet; ?>">
<?php endforeach; ?>
</head>
<body>
    <?= $headerHook; ?>
    <nav>
        <?= $this->RenderNav($page); ?>
    </nav>
    <header><h1><?=$page->GetName(); ?></h1></header>
    <main>
        <?php include('templates' . DIRECTORY_SEPARATOR . $page->GetTemplate() . '.php'); ?>
    </main>
    <script type="text/javascript" src="/scripts/jquery.js"></script>
    <script type="text/javascript" src="/vendors/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/vendors/sweetalert.min.js"></script>
    <script type="text/javascript" src="/scripts/scripts.min.js"></script>
<?php if($this->application->PluginExists('Offline') && HTTPS): ?>
    <script type="text/javascript" src="/js/Offline/offline.min.js"></script>
<?php endif; ?>
<?php foreach($this->application->GetScriptDependency() as $script): ?>
    <script type="text/javascript" src="<?= $script; ?>"></script>
<?php endforeach; ?>
</body>
</html>