<?php
if(!isset($input, $errormessages, $attributes)){
    throw new \Form\view\ElementMissingException();
}

/*
 * Use variable $input to access methods
 *
 * @var $input \Form\model\Element
 * @var $errormessage string
 * @var $attributes string
 */
?>

<div class="form-group">
    <label for="<?php echo $input->GetName(); ?>"><?php echo $input->GetLabel(); ?></label>
    <div class="onoffswitch">
        <input type="checkbox" name="<?php echo $input->GetName(); ?>" class="onoffswitch-checkbox" id="<?php echo $input->GetName(); ?>" <?php echo $input->GetValue() ? 'checked="checked"' : ''; ?> <?php echo $attributes; ?> />
        <label class="onoffswitch-label" for="myonoffswitch">
            <span class="onoffswitch-inner"></span>
            <span class="onoffswitch-switch"></span>
        </label>
    </div>
    <?php echo $errormessages; ?>

</div>
