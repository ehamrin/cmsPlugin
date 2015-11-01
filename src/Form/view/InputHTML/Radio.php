<?php
if(!isset($input, $errormessages, $attributes)){
    throw new \Form\view\ElementMissingException();
}

/*
 * Use variable $input to access methods
 *
 * @var $input \Form\model\input\dev\Radio
 * @var $errormessage string
 * @var $attributes string
 */
?>

<div class="form-group">
    <label><?php echo $input->GetLabel(); ?></label>
    <?php foreach($input->GetOptions() as $option): ?>
        <div class="radio-group">
            <input id="<?php echo $input->GetName() . '_' . $option->GetName(); ?>" name="<?php echo $input->GetName(); ?>" type="radio" value="<?php echo $option->GetValue(); ?>" <?php echo $option->GetValue() == $input->GetValue() ? 'checked="checked"' : ''; ?> />
            <label for="<?php echo $input->GetName() . '_' . $option->GetName(); ?>"><?php echo $option->GetName(); ?></label>
        </div>
    <?php endforeach; ?>
    <?php echo $errormessages; ?>

</div>
