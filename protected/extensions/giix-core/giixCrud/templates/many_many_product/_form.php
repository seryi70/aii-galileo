<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  _form  Form for <?php echo $this->modelClass;?>  create and update operations,rendered inside fancybox
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->
<?php
$related_this_Class = $this->getRelations($this->modelClass);
$relatedModelClass = $related_this_Class[0][3];
$relationName = $related_this_Class[0][0];

$related_Related_Class = $this->getRelations($relatedModelClass);
$relatedRelatedModelClass = $related_Related_Class[0][3];
$relatedRelationName = $related_Related_Class[0][0];
?>
<div id="form_wrapper" class="client-val-form">
    <?php echo '<?php '; ?>    if ($model->isNewRecord) : <?php echo '?>'; ?>
    <h3 id="create_header"><span style="color:#4079C8">Create New <?php echo $this->modelClass;?></span></h3>
    <?php echo '<?php '; ?>   elseif (!$model->isNewRecord): <?php echo '?>'; ?>
    <h3 id="update_header">
        Update <?php echo $this->modelClass;?> <span style="color:#4079C8">with ID: <?php echo '<?php '; ?>echo $model->id<?php echo '?>'; ?></span>
    </h3>
    <?php echo '<?php '; ?>  endif; <?php echo '?>'; ?>
    <?php echo '<?php '; ?>
    $val_error_msg = 'Error.<?php echo $this->modelClass;?> was not saved.';
    $val_success_message = ($model->isNewRecord) ?
    '<?php echo $this->modelClass;?> was created successfuly.' :
    '<?php echo $this->modelClass;?> was updated successfuly.';
    <?php echo '?>'; ?>
    <div id="success-note" class="notification success png_bg" style="display:none;">
        <a href="#" class="close">
            <img
                src="<?php echo '<?php '; ?>echo Yii::app()->request->baseUrl;<?php echo '?>'; ?>/js_plugins/ajaxform/images/icons/cross_grey_small.png"
                title="Close this notification" alt="close"/>
        </a>

        <div>
            <?php echo '<?php '; ?>echo $val_success_message;<?php echo '?>'; ?>

        </div>
    </div>

    <div id="error-note" class="notification errorshow png_bg" style="display:none;">
        <a href="#" class="close">
            <img
                src="<?php echo '<?php '; ?>echo Yii::app()->request->baseUrl;<?php echo '?>'; ?>/js_plugins/ajaxform/images/icons/cross_grey_small.png"
                title="Close this notification" alt="close"/>
        </a>

        <div>
            <?php echo '<?php '; ?>echo $val_error_msg;<?php echo '?>'; ?>

        </div>
    </div>

    <div id="ajax-form" class="form">
        <?php echo '<?php  '; ?>

        $formId = '<?php echo $this->class2var($this->modelClass);?>-form';
        $actionUrl = ($model->isNewRecord) ?
        CController::createUrl('<?php echo  $this->getControllerID();?>/create') :
        CController::createUrl('<?php echo  $this->getControllerID();?>/update');

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
        <?php echo '?>'; ?>

        <?php echo '<?php '; ?> echo $form->errorSummary($model,
        '
        <div style="font-weight:bold">Please correct these errors:</div>
        ', NULL, array('class' => 'errorsum notification errorshow png_bg'));
        <?php echo '?>'; ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php
        foreach ($this->tableSchema->columns as $column)
        {
            if ($column->autoIncrement)
                continue;
            ?>
            <div class="row">
                <?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n"; ?>
                <?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
                <span id="success-<?php echo $this->modelClass; ?>_<?php echo $column->name; ?>"
                      class="hid input-notification-success  success png_bg right"></span>

                <div>
                    <small><?php //echo Yii::t('admin', ''); ?></small>
                </div>
                <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <?php echo '<?php '; ?> echo $form->fileField($model,'<?php echo $this->class2var($this->modelClass);?>
            _image'); <?php echo '?>'; ?>
            <div>
                <small><?php echo '<?php '; ?>echo '<?php echo $this->modelClass;?> Image';<?php echo '?>'; ?></small>
            </div>
        </div>

        <div class="row">
            <select data-placeholder="Choose <?php echo $this->pluralize($relatedModelClass); ?>..."
                    name="<?php echo $this->modelClass;?>[<?php echo $relationName?>][]"
                    class="<?php echo $this->class2var($relatedModelClass);?>-select" multiple="true"
                    style="width: 250px;" tabindex="-1">
                <option value=""></option>
                <?php echo '<?php '; ?> $this->related_opts($model);<?php echo '?>'; ?>
            </select>

            <div>
                <small><?php echo '<?php '; ?>echo '<?php echo $this->pluralize($relatedModelClass); ?> in
                    this <?php echo $this->modelClass;?>';<?php echo '?>'; ?></small>
            </div>
        </div>

        <div class="row">
            <input type="hidden" name="YII_CSRF_TOKEN"
                   value="<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"/>
        </div>

        <?php echo '<?php '; ?> if (!$model->isNewRecord): <?php echo '?>'; ?>
        <div class="row">
            <?php echo '<?php '; ?>echo $form->hiddenField($model, 'update_id', array('value'=>$model->id))
            ;<?php echo '?>'; ?>
        </div>
        <?php echo '<?php '; ?>endif; <?php echo '?>'; ?>

        <div class="row buttons">
            <?php echo '<?php '; ?>echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' =>
            'button align-right'));<?php echo '?>'; ?>
        </div>
        <?php echo '  <?php '; ?> $this->endWidget();<?php echo '?>'; ?>
    </div>
    <!-- form -->
</div>
<script type="text/javascript">
    $(function () {
        //multiple selection list
        $(".<?php echo $this->class2var($relatedModelClass);?>-select").chosen();
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