<form action="" method="POST" class="inline-1-2">
    <?= $this->message ?>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" />
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" />
    </div>

<?php foreach($permissions as $permission):
    /* @var $permission \plugin\Authentication\model\Permission */
?>
        <div class="form-group">
            <label for="<?= $permission->GetPermission() ?>"><?= $permission->GetName(); ?></label>
            <input id="<?= $permission->GetPermission() ?>" name="permission[<?= $permission->GetPermission() ?>]" type="checkbox" value="<?= $permission->GetPermission() ?>" />
        </div>

    <?php endforeach; ?>
    <button type="submit" name="add_submit">Save</button>
</form>