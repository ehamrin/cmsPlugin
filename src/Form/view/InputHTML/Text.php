<?php
if(!isset($input, $errormessages, $attributes)){
    throw new \Form\view\ElementMissingException();
}

/*
 * Use variable $input to access methods
 *
 * @var $input \Form\model\dev\Text
 * @var $errormessage string
 * @var $attributes string
 */
?>

<div class="form-group <?php echo $input->GetType(); ?>">
    <label for="<?php echo $input->GetName(); ?>"><?php echo $input->GetLabel(); ?></label>
    <input type="<?php echo $input->GetType(); ?>" name="<?php echo $input->GetName(); ?>" id="<?php echo $input->GetName(); ?>" value="<?php echo $input->GetValue(); ?>"  <?php echo $attributes; ?>/>
    <?php echo $errormessages; ?>

</div>
