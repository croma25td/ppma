<?php
    /* @var Entry $model */
    /* @var CActiveForm $form */
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id'    => 'entry-form',
    'focus' => array($model, 'name'),
)); ?>

    <?php echo $form->hiddenField($model, 'id'); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('autocomplete' => 'off')); ?>
    <?php echo $form->error($model, 'name'); ?>

    <?php echo $form->labelEx($model, 'username'); ?>
    <?php echo $form->textField($model, 'username', array('autocomplete' => 'off')); ?>
    <?php echo $form->error($model, 'username'); ?>

    <?php echo $form->labelEx($model, 'password'); ?>

    <div class="row collapse">
        <div class="nine columns">
            <?php echo $form->passwordField($model, 'password', array('autocomplete' => 'off', 'required' => 'required')); ?>
        </div>
	<div class="one columns">
            <span onclick="randomPassword()" class="postfix button secondary expand generate-password" title="generate password">
                <i  class="foundicon-settings"></i>
            </span>
        </div>
        <div class="one columns">
            <span class="postfix button secondary expand copy-to-clipboard" data-clipboard-text="<?php echo CHtml::value($model, 'password') ?>" title="copy password"><i class="foundicon-page"></i></span>
        </div>
        <div class="one columns">
            <span class="postfix button secondary expand show-hide-password">
                <i class="foundicon-access-eyeball"></i>
            </span>
        </div>
    </div>
    <?php echo $form->error($model, 'password'); ?>

    <?php echo $form->labelEx($model, 'url'); ?>
    <?php echo $form->textField($model, 'url'); ?>
    <?php echo $form->error($model, 'url'); ?>

    <?php echo $form->labelEx($model, 'tagList'); ?>
    <?php echo $form->textField($model, 'tagList', array('placeholder' => 'seperate by commas')); ?>
    <?php echo $form->error($model, 'tagList'); ?>

    <?php echo $form->labelEx($model, 'comment'); ?>
    <?php echo $form->textArea($model, 'comment', array('rows' => 5)); ?>
    <?php echo $form->error($model, 'comment'); ?>

    <?php echo CHtml::submitButton('Save', array('class' => 'button radius'))?>

<?php $this->endWidget(); ?>

<SCRIPT LANGUAGE="JavaScript">

function randomPassword(length)
{

  var length = 16;
  var charset = "!%&?*+-<>'@#23456789ABCDEFGHJKLMNOPRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
  var password = "";
  for (var i = 0, n = charset.length; i < length; ++i) {
      password += charset.charAt(Math.floor(Math.random() * n));
  }

  document.getElementById('Entry_password').value = password;
  document.getElementById('Entry_password').setAttribute('type','text');
}
</script>
