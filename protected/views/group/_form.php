<!--/**
 * MANY_MANY  Ajax Crud Administration Demo
 *  _form  Form for Group create and update operations,rendered inside fancybox
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->
<div id="form_wrapper" class="client-val-form">


<?php   if ($_POST['create_root']=='true' && $model->isNewRecord) : ?>        <h3 id="create_header"><span style="color:#4079C8">Create New Root Group</span></h3>


    <?php elseif ($model->isNewRecord) : ?>    <h3 id="create_header"><span style="color:#4079C8">Create New Group</span></h3>

    <?php    elseif (!$model->isNewRecord): ?>    <h3 id="update_header">Update Group <span  style="color:#4079C8"></span></h3>

    <?php   endif; ?>

    <?php
    $val_error_msg = 'Error.Group was not saved.';
    $val_success_message = ($model->isNewRecord) ?
    'Group was created successfuly.' :
    'Group was updated successfuly.';
    ?>

    <div id="success-note" class="notification success png_bg" style="display:none;">
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
        $formId = 'group-form';
        $actionUrl = ($model->isNewRecord) ?
        ( ($_POST['create_root']!='true')?CController::createUrl('group/create'):
        CController::createUrl('group/createRoot')):
        CController::createUrl('group/update');

        $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
//        'value'=> $namevalue,
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
        }',
        ),
        ));
        ?>
        <?php

        $namevalue=(!$model->isNewRecord) ?$model->name:$newname ;
        ?>
        <?php  echo $form->errorSummary($model,
        '<div style="font-weight:bold">Please correct these errors:</div>
        ', NULL, array('class' => 'errorsum notification errorshow png_bg'));
        ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="row">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model, 'name', array('maxlength' => 50,'value'=> $namevalue)); ?>
            <span id="success-Group_name"
                  class="hid input-notification-success  success png_bg right"></span>
            <div>
                <small></small>
            </div>
            <?php echo $form->error($model,'name'); ?>
        </div>
                <div class="row">
            <?php echo $form->labelEx($model,'description'); ?>
            <?php echo $form->textField($model, 'description', array('maxlength' => 200)); ?>
            <span id="success-Group_description"
                  class="hid input-notification-success  success png_bg right"></span>
            <div>
                <small></small>
            </div>
            <?php echo $form->error($model,'description'); ?>
        </div>


<!--        <div class="row">-->
<!--            <?php // echo $form->fileField($model,'group_image'); ?>-->
<!--            <div>-->
<!--                <small><?php //echo 'Group Image';?></small>-->
<!--            </div>-->
<!--        </div>-->


        <div class="row">
            <select data-placeholder="Choose Planets..."
                    name="Group[planets][]"
                    class="planet-select" multiple="true"
                    style="width: 250px;" tabindex="-1">
                <option value=""></option>
                <?php  $this->related_opts($model);?>
            </select>
            <div>
                <small><?php echo 'Planets in this Group';?></small>
            </div>
        </div>

 <div class="row">
        <input type="hidden" name="YII_CSRF_TOKEN"   value="<?php echo Yii::app()->request->csrfToken;?>"/>
</div>

<div class="row">
        <input type="hidden" name="parent_id" value="<?php  echo $_POST['parent_id'];?>"/>
</div>


        <?php  if (!$model->isNewRecord): ?>
        <div class="row">
        <input type="hidden" name="update_id"    value="<?php  echo $_POST['update_id']; ?>"/>
         </div>
        <?php endif; ?>


            <?php  if (!$model->isNewRecord): ?>
            <div class="row">
                <?php echo $form->hiddenField($model, 'group_id', array('value' => $model->id));?>
            </div>
            <?php endif; ?>


            <div class="row buttons">
                <?php
                echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'button align-right'));
                ?>
            </div>
            <?php  $this->endWidget();?>
        </div>
        <!-- form -->

    </div>

</div>
<script type="text/javascript">
    $(function () {
        //multiple selection list
        $(".planet-select").chosen();

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