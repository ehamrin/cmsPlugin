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
    <button type="submit" name="<?php echo $input->GetName(); ?>" id="<?php echo $input->GetName(); ?>" value="<?php echo $input->GetValue(); ?>" <?php echo $attributes; ?>><?php echo $input->GetValue(); ?></button>
</div>
