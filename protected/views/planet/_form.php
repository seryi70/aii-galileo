<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  _form  Form for Planet  create and update operations,rendered inside fancybox
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->
<div id="form_wrapper" class="client-val-form">
    <?php     if ($model->isNewRecord) : ?>    <h3 id="create_header"><span style="color:#4079C8">Create New Planet</span></h3>
    <?php    elseif (!$model->isNewRecord): ?>    <h3 id="update_header">
      Update Planet <span  style="color:#4079C8">with ID: <?php echo $model->id?></span>
     </h3>
    <?php   endif; ?>    <?php     $val_error_msg = 'Error.Planet was not saved.';
    $val_success_message = ($model->isNewRecord) ?
    'Planet was created successfuly.' :
    'Planet was updated successfuly.';
    ?>    <div id="success-note" class="notification success png_bg" style="display:none;">
        <a href="#" class="close">
             <img
                src="<?php echo Yii::app()->request->baseUrl;?>/js_plugins/ajaxform/images/icons/cross_grey_small.png"
                title="Close this notification" alt="close"/>
         </a>
        <div>
            <?php echo $val_success_message;?>
        </div>
    </div>

    <div id="error-note" class="notification errorshow png_bg" style="display:none;">
        <a href="#" class="close">
            <img
                src="<?php echo Yii::app()->request->baseUrl;?>/js_plugins/ajaxform/images/icons/cross_grey_small.png"
                title="Close this notification" alt="close"/>
        </a>
        <div>
            <?php echo $val_error_msg;?>
        </div>
    </div>

  <div id="ajax-form" class="form">
     <?php
        $formId = 'planet-form';
        $actionUrl = ($model->isNewRecord) ?
                CController::createUrl('planet/create') :
                CController::createUrl('planet/update');

        $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
        <?php  echo $form->errorSummary($model,
        '<div style="font-weight:bold">Please correct these errors:</div>
        ', NULL, array('class' => 'errorsum notification errorshow png_bg'));
        ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model, 'name', array('maxlength' => 100)); ?>
        <span id="success-Planet_name"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
        <?php echo $form->error($model,'name'); ?>
    </div>
        <div class="row">
        <?php echo $form->labelEx($model,'planetGSM'); ?>
        <?php echo $form->textField($model, 'planetGSM', array('maxlength' => 20)); ?>
        <span id="success-Planet_planetGSM"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
        <?php echo $form->error($model,'planetGSM'); ?>
    </div>
        <div class="row">
        <?php echo $form->labelEx($model,'address'); ?>
        <?php echo $form->textField($model, 'address', array('maxlength' => 45)); ?>
        <span id="success-Planet_address"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
        <?php echo $form->error($model,'address'); ?>
    </div>
        <div class="row">
        <?php echo $form->labelEx($model,'NrSatellites'); ?>
        <?php echo $form->textField($model, 'NrSatellites'); ?>
        <span id="success-Planet_NrSatellites"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
        <?php echo $form->error($model,'NrSatellites'); ?>
    </div>
<!--//CS: Install || Update time-->
                <div class="row">
                    <?php if ($model->isNewRecord) {
                            echo $form->labelEx($model,'installDate');
                            echo $form->textField($model,'installDate',
                            array('size'=>25,'value'=>date("Y-m-d H:i:s"),'readonly'=>'readonly' ));
                        }
                        else {
                            echo $form->labelEx($model,'updateDate');
                            echo $form->textField($model,'updateDate',
                            array('size'=>25,'value'=>date("Y-m-d H:i:s"),'readonly'=>'readonly' ));
                        }?>
                    <span id="<?php if ($model->isNewRecord) {
                            echo 'success-Planet_installDate';
                        }
                        else {
                            echo 'success-Planet_updateDate';
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

    <div class="row">
      <select data-placeholder="Choose Groups..."
            name="Planet[groups][]"
            class="group-select" multiple="true"
            style="width: 250px;" tabindex="-1">
        <option value=""></option>
        <?php  $this->related_opts($model);?>      </select>
      <div>
        <small><?php echo 'Groups in this Planet';?></small>
      </div>
    </div>

    <div class="row">
      <input type="hidden" name="YII_CSRF_TOKEN"   value="<?php echo Yii::app()->request->csrfToken;?>"/>
    </div>

<?php  if (!$model->isNewRecord): ?>    <div class="row">
      <?php echo $form->hiddenField($model, 'update_id', array('value'=>$model->id)) ;?>    </div>
<?php endif; ?>
    <div class="row buttons">
      <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'button align-right'));?>    </div>
  <?php  $this->endWidget();?>  </div>
<!-- form -->
</div>
<script  type="text/javascript">
      $(function () {
        //multiple selection list
        $(".group-select").chosen();
        //Close button on top of ajax response div
        $(".close").click(
            function () {
                $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                $(this).slideUp(600);
            });
            return false;
          }
        );
    });
</script>