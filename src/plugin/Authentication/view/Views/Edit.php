<?php /* @var $user \plugin\Authentication\model\User */ ?>

<form action="" method="POST" class="inline-1-2">
    <?= $this->message ?>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= $user->GetUsername() ?>"/>
    </div>

<?php foreach($permissions as $permission):
/* @var $permission \plugin\Authentication\model\Permission */
?>
    <div class="form-group">
        <label for="<?= $permission->GetPermission() ?>"><?= $permission->GetName(); ?></label>
        <input id="<?= $permission->GetPermission() ?>" name="permission[<?= $permission->GetPermission() ?>]" type="checkbox" value="<?= $permission->GetPermission() ?>" <?= ($user->Can($permission->GetPermission()) ? 'checked=checked': '') ?>/>
    </div>

<?php endforeach; ?>
    <button type="submit" name="edit_submit">Save</button>
</form>

<form action="" method="POST" class="inline-1-2">

    <div class="form-group">
        <label for="old">Old password</label>
        <input type="password" name="old"/>
    </div>
    <div class="form-group">
        <label for="new">New password</label>
        <input type="password" name="new"/>
    </div>
    <div class="form-group">
        <label for="new_repeat">Repeat password</label>
        <input type="password" name="new_repeat"/>
    </div>

    <button type="submit" name="edit_password">Update password</button>
</form>