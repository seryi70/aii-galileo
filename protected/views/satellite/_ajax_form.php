<!--
 * Ajax Crud Administration Form
 * Satellite *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
-->

<div id="satellite_form_con" class="client-val-form">

    <?php  if ($model->isNewRecord) : ?>    <h3 id="create_header"><span style="color:#1113c7">Create New Satellite</span></h3>
    <?php  elseif (!$model->isNewRecord): ?>    <h3 id="update_header"> Update Satellite <span  style="color:#180fc7">with ID: <?php echo
$model->id;  ?>  </h3>
    <?php  endif;  ?>    <?php      $val_error_msg = 'Error.Satellite was not saved.';
    $val_success_message = ($model->isNewRecord) ?
            'Satellite was created successfuly.' :
            'Satellite  was updated successfuly.';
  ?>

    <div id="success-note" class="notification success png_bg"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_success_message;  ?>        </div>
    </div>

    <div id="error-note" class="notification errorshow png_bg"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_error_msg;  ?>        </div>
    </div>

    <div id="ajax-form"  class='form'>
<?php
   $formId='satellite-form';
   $actionUrl =  ($model->isNewRecord)?
               CController::createUrl('satellite/ajax_create'):
               CController::createUrl('satellite/ajax_update');

    $form=$this->beginWidget('CActiveForm', array(
     'id'=>$formId,
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'action' => $actionUrl,
        // 'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        'focus' => array($model, 'name'),
        'errorMessageCssClass' => 'input-notification-error error-simple png_bg',
        'clientOptions' => array('validateOnSubmit' => true,
        'validateOnType' => false,
        'afterValidate' => 'js:function(form,data,hasError){ $.js_afterValidate(form,data,hasError); }',
        'errorCssClass' => 'err',
        'successCssClass' => 'suc',
        'afterValidateAttribute' => 'js:function(form, attribute, data, hasError){
        $.js_afterValidateAttribute(form, attribute, data, hasError);
        }'
        ),
        ));

     ?>
    <?php echo $form->errorSummary($model, '
    <div style="font-weight:bold">Please correct these errors:</div>
    ', NULL, array('class' => 'errorsum notification errorshow png_bg')); ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>


    <div class="row">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name',array('maxlength'=>25)); ?>
        <span id="success-Satellite_name"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'name'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,'active'); ?>
            <?php echo $form->textField($model,'active'); ?>
        <span id="success-Satellite_active"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'active'); ?>
    </div>

<!-- TODO:  -->
        <div class="row">
            <select data-placeholder="Parent Planet..."
                    name="Satellite[parentPlanetID]"
                    class="planet-select" multiple="false"
                    style="width: 300px;" tabindex="-1">
                <option value=""></option>
                <?php  $this->related_opts($model);?>
            </select>
        <span id="success-Satellite_parentPlanetID"
              class="hid input-notification-success  success png_bg right"></span>
            <div>
                <small><?php echo 'Assign the Sattelite to a Planet';?></small>
            </div>
        </div>
<!-- TODO:  -->

        <div class="row">
            <?php echo $form->labelEx($model,'address'); ?>
            <?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>45)); ?>
        <span id="success-Satellite_address"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'address'); ?>
    </div>

<!--//CS: Install || Update time-->
        <div class="row">
            <?php if ($model->isNewRecord) {
            echo $form->labelEx($model,'installDate');
            echo $form->textField($model,'installDate',
                array('value'=>date("Y-m-d H:i:s"),'readonly'=>'readonly' ));
        }
        else {
            echo $form->labelEx($model,'updateDate');
            echo $form->textField($model,'updateDate',
                array('value'=>date("Y-m-d H:i:s"),'readonly'=>'readonly' ));
        }?>
            <span id="<?php if ($model->isNewRecord) {
                echo 'success-Satellite_installDate"';
            }
            else {
                echo 'success-Satellite_updateDate';
            }?>"
                  class="hid input-notification-success  success png_bg right"></span>
            <div>
                <small></small>
            </div>
            <?php if ($model->isNewRecord) {
            echo $form->error($model,'installDate');
        }else{
            echo $form->error($model,'updateDate'); }?>
        </div>
<!--//CS: Install || Update time-->
<!--//CS: TODO: fix deactivate 0000-00-00 00:00:00-->
        <div class="row">
            <?php echo $form->labelEx($model,'deactivateDate'); ?>
            <?php echo $form->textField($model,'deactivateDate'); ?>
        <span id="success-Satellite_deactivateDate"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small>Shut down time</small>
        </div>
            <?php echo $form->error($model,'deactivateDate'); ?>
        </div>


    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="row buttons">
        <?php   echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save',array('class' =>
        'button align-right')); ?>    </div>

  <?php  $this->endWidget(); ?></div>
    <!-- form -->

</div>
<script type="text/javascript">

    //Close button:

    $(".close").click(
            function () {
                $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                    $(this).slideUp(600);
                });
                return false;
            }
    );


</script>


