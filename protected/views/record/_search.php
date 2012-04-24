<div class="wide form">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'action'=> Yii::app()->createUrl($this->route),
    'method'=> 'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model, 'satelliteID'); ?>
        <?php echo $form->textField($model, 'satelliteID', array('size'     => 25,
                                                                 'maxlength'=> 25)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'recordDate'); ?>
        <?php echo $form->textField($model, 'recordDate'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'recordData'); ?>
        <?php echo $form->textField($model, 'recordData'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->