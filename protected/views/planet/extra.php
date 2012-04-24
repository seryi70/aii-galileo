=======================_form.php=======================================
</div>
<div class="row">
    <?php echo $form->labelEx($model, 'extra1'); ?>
    <?php echo $form->textField($model, 'extra1', array('maxlength' => 45)); ?>
    <span id="success-Planet_extra1"
          class="hid input-notification-success  success png_bg right"></span>

    <div>
        <small></small>
    </div>
    <?php echo $form->error($model, 'extra1'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model, 'extra2'); ?>
    <?php echo $form->textField($model, 'extra2', array('maxlength' => 45)); ?>
    <span id="success-Planet_extra2"
          class="hid input-notification-success  success png_bg right"></span>

    <div>
        <small></small>
    </div>
    <?php echo $form->error($model, 'extra2'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model, 'extra3'); ?>
    <?php echo $form->textField($model, 'extra3'); ?>
    <span id="success-Planet_extra3"
          class="hid input-notification-success  success png_bg right"></span>

    <div>
        <small></small>
    </div>
    <?php echo $form->error($model, 'extra3'); ?>
</div>
<div class="row">
    <?php echo $form->labelEx($model, 'extra4'); ?>
    <?php echo $form->textField($model, 'extra4'); ?>
    <span id="success-Planet_extra4"
          class="hid input-notification-success  success png_bg right"></span>

    <div>
        <small></small>
    </div>
    <?php echo $form->error($model, 'extra4'); ?>
</div>


<div class="row">
    <?php  echo $form->fileField($model, 'planet_image'); ?>
    <div>
        <small><?php echo 'Planet Image';?></small>
    </div>
</div>

=======================_search.php=======================================

<div class="row">
    <?php echo $form->label($model, 'extra1'); ?>
    <?php echo $form->textField($model, 'extra1', array('maxlength' => 45)); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'extra2'); ?>
    <?php echo $form->textField($model, 'extra2', array('maxlength' => 45)); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'extra3'); ?>
    <?php echo $form->textField($model, 'extra3'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'extra4'); ?>
    <?php echo $form->textField($model, 'extra4'); ?>
</div>
=======================_view.php=======_frontend_view.php=========================

<div class="thumb_wrapper" id="tmb_wrapper_<?php echo $data->id;?>">
    <img id="<?php echo 'thumb_' . $data->id;?>"
         src="<?php   echo $img_url;?>?<?php echo time();?>"
         title="<?php echo $data->id;?>"/>
</div>
=======================view.php==================================================
<div class="large_pic_wrapper" id="large_wrapper_<?php echo $model->id;?>">
    <img id="<?php echo 'large_' . $model->id;?>"
         src="<?php   echo $img_url;?>?<?php echo time();?>"
         title="image_<?php echo $model->id;?>"/>
</div>

