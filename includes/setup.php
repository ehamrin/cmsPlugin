<?php
$my_file = APP_ROOT . 'src' . DIRECTORY_SEPARATOR . 'Settings.php';

if(file_exists($my_file)){
    die("Something is not right here...");
}

if (isset($_POST['submit'])) {
    $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "settings-default.php");
    $content = str_replace("[DatabaseDSN::Placeholder]", $_POST['db_dsn'], $content);
    $content = str_replace("[DatabaseUsername::Placeholder]", $_POST['db_username'], $content);
    $content = str_replace("[DatabasePassword::Placeholder]", $_POST['db_password'], $content);
    $content = str_replace("[DatabaseDatabase::Placeholder]", $_POST['db_database'], $content);

    $handle = fopen($my_file, 'w');
    fwrite($handle, $content);

    try {
        $conn = Database::GetConnection();

        $authentication = new \plugin\Authentication\model\UserModel();

        $authentication->Install();
        $user = new \plugin\Authentication\model\User($_POST['cms_username'], $_POST['cms_password']);
        $authentication->Create($user);


        $settings = new \plugin\Settings\model\SettingModel();

        $settings->Install();
        $setting = new \plugin\Settings\model\Setting("page-site-title", $_POST['title'], "The name of your website");
        $settings->Save($setting);

    } catch (\Exception $e) {
        debug($e->getMessage());
        unlink($my_file);
    }
}
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Administration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/normalize.css?v=1.0">
    <link rel="stylesheet" href="/vendors/sweetalert.css?v=1.0">
    <link rel="stylesheet" href="/css/admin.css?v=1.0">
    <script src="/scripts/jquery.js"></script>
    <script src="/vendors/sweetalert.min.js"></script>
    <script src="/scripts/scripts.js"></script>

</head>

<body>
<div id="login_form">
    <form method="POST">
        <fieldset>
            <legend>Database setup</legend>
            <div class="form-group">
                <label for="db_dsn">Database DSN</label>
                <input type="text" value="<?php echo(isset($_POST['db_dsn']) ? $_POST['db_dsn'] : 'localhost'); ?>" name="db_dsn" id="db_dsn"/>
            </div>
            <div class="form-group">
                <label for="db_username">Database username</label>
                <input type="text"
                       value="<?php echo(isset($_POST['db_username']) ? $_POST['db_username'] : 'root'); ?>" name="db_username" id="db_username"/>
            </div>
            <div class="form-group">
                <label for="db_password">Database Password</label>
                <input type="password"
                       value="<?php echo(isset($_POST['db_password']) ? $_POST['db_password'] : ''); ?>" name="db_password" id="db_password"/>
            </div>
            <div class="form-group">
                <label for="db_database">Database name</label>
                <input type="text" value="<?php echo(isset($_POST['db_database']) ? $_POST['db_database'] : ''); ?>" name="db_database" id="db_database"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>Main setup</legend>
            <div class="form-group">
                <label for="title">Site title</label>
                <input type="text" value="<?php echo(isset($_POST['title']) ? $_POST['title'] : ''); ?>" name="title" id="title"/>
            </div>
        </fieldset>
        <fieldset>
            <legend>User setup</legend>
            <div class="form-group">
                <label for="cms_username">CMS Username</label>
                <input type="text"
                       value="<?php echo(isset($_POST['cms_username']) ? $_POST['cms_username'] : 'admin'); ?>" name="cms_username" id="cms_username"/>
            </div>
            <div class="form-group">
                <label for="cms_password">CMS Password</label>
                <input type="password"
                       value="<?php echo(isset($_POST['cms_password']) ? $_POST['cms_password'] : ''); ?>" name="cms_password" id="cms_password"/>
            </div>
        </fieldset>
        <div class="form-group">
            <button class="button" type="submit" name="submit">Submit</button>
        </div>
    </form>
</div>
</body>
</html>