<?php
if(!isset($input, $errormessages, $attributes)){
    throw new \Form\view\ElementMissingException();
}

/*
 * Use variable $input to access methods
 *
 * @var $input \Form\model\Element
 * @var $errormessage HTMLstring
 */
?>
<p class="info">
    <input type="checkbox" name="<?php echo $input->GetName(); ?>" id="<?php echo $input->GetName(); ?>" <?php echo $input->GetValue() ? 'checked="checked"' : ''; ?>/>
    <label for="<?php echo $input->GetName(); ?>"><?php echo $input->GetLabel(); ?></label>
    <?php echo $errormessages; ?>

</p>