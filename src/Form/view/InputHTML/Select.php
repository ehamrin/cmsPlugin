<?php
if(!isset($input, $errormessages, $attributes)){
    throw new \Form\view\ElementMissingException();
}

/*
 * Use variable $input to access methods
 *
 * @var $input \Form\model\input\dev\Select
 * @var $errormessage string
 * @var $attributes string
 */

?>

<div class="form-group">
    <label for="<?php echo $input->GetName(); ?>"><?php echo $input->GetLabel(); ?></label>
    <select name="<?php echo $input->GetName(); ?>" id="<?php echo $input->GetName(); ?>" <?php echo $attributes; ?>>
<?php foreach($input->GetOptions() as $option):
    /* @var $option \Form\model\Option */
?>
        <option value="<?php echo $option->GetValue(); ?>" <?php echo $option->GetValue() == $input->GetValue() ? 'selected' : ''; ?>><?php echo $option->GetName(); ?></option>
<?php endforeach; ?>
    </select>
    <?php echo $errormessages; ?>

</div>
