<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?= $page->GetName(); ?></title>
    <meta name="description" content="<?= $page->GetName(); ?>">
    <meta name="author" content="Erik Hamrin">
    <meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1.0, user-scalable=false">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/vendors/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css?v=1.0">
    <script src="/scripts/jquery.js"></script>
    <script type="text/javascript" src="/scripts/scripts.js"></script>
    <script type="text/javascript" src="/vendors/fancybox/jquery.fancybox.pack.js"></script>
</head>

<body>
<?= $headerHook; ?>
<nav>
    <?= $this->RenderNav($page); ?>
</nav>
<header><h1><?=$page->GetName(); ?></h1></header>
<main>

    <div class="wrapper">
        <?= $page->GetContent();; ?>
    </div>
</main>

</body>
</html>