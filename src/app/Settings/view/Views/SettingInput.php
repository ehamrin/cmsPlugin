<h2><?= $plugin; ?></h2>
<?php foreach($settings as $setting): ?>
<div class="form-group">
    <p>
        <input type="text" name="settings[<?= $setting->GetName(); ?>]" value="<?= $value = $model->Get($setting->GetName()) ? $model->Get($setting->GetName())->GetValue() : $setting->GetValue(); ?>" />
        <span><?= $setting->GetDescription(); ?></span>
    </p>
</div>
<?php endforeach; ?>
