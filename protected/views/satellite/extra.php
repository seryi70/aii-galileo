=======================_ajax_form.php======================================

        <div class="row">
            <?php echo $form->labelEx($model,'error'); ?>
            <?php echo $form->textField($model,'error'); ?>
        <span id="success-Satellite_error"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'error'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'parentPlanetID'); ?>
            <?php echo $form->textField($model,'parentPlanetID',array('size'=>10,'maxlength'=>10)); ?>
        <span id="success-Satellite_parentPlanetID"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'parentPlanetID'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'extra1'); ?>
            <?php echo $form->textField($model,'extra1',array('size'=>45,'maxlength'=>45)); ?>
        <span id="success-Satellite_extra1"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'extra1'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'extra2'); ?>
            <?php echo $form->textField($model,'extra2',array('size'=>45,'maxlength'=>45)); ?>
        <span id="success-Satellite_extra2"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'extra2'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'extra3'); ?>
            <?php echo $form->textField($model,'extra3'); ?>
        <span id="success-Satellite_extra3"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'extra3'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'extra4'); ?>
            <?php echo $form->textField($model,'extra4'); ?>
        <span id="success-Satellite_extra4"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'extra4'); ?>
    </div>

=======================_search.php======================================
	<div class="row">
		<?php echo $form->label($model,'extra1'); ?>
		<?php echo $form->textField($model,'extra1',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'extra2'); ?>
		<?php echo $form->textField($model,'extra2',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'extra3'); ?>
		<?php echo $form->textField($model,'extra3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'extra4'); ?>
		<?php echo $form->textField($model,'extra4'); ?>
	</div>
