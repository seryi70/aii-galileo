<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'                  => 'measurement-form',
    'enableAjaxValidation'=> false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'satelliteID'); ?>
        <?php echo $form->textField($model, 'satelliteID', array('size'     => 25,
                                                                 'maxlength'=> 25)); ?>
        <?php echo $form->error($model, 'satelliteID'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'recordDate'); ?>
        <?php echo $form->textField($model, 'recordDate', array('size' => 25,
                                                                'value'=> time()));
        echo "Unix time:" . date("Y-m-d H:i:s"); //strtotime() ?>
        <?php echo $form->error($model, 'recordDate'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'recordData'); ?>
        <?php echo $form->textField($model, 'recordData'); ?>
        <?php echo $form->error($model, 'recordData'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->