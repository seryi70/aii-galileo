<div class="wide form">

<?php $form = $this->beginWidget('GxActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id', array('maxlength' => 10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength' => 100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'planetGSM'); ?>
		<?php echo $form->textField($model, 'planetGSM', array('maxlength' => 20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'address'); ?>
		<?php echo $form->textField($model, 'address', array('maxlength' => 45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'NrSatellites'); ?>
		<?php echo $form->textField($model, 'NrSatellites'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'installDate'); ?>
		<?php echo $form->textField($model, 'installDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'updateDate'); ?>
		<?php echo $form->textField($model, 'updateDate'); ?>
	</div>

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

	<div class="row buttons">
		<?php echo GxHtml::submitButton(Yii::t('app', 'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
