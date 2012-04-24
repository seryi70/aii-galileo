<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.1
 *
 * module form
 */

$this->breadcrumbs = array(
    Yii::t('importcsvModule.importcsv', 'Import') . " CSV",
);
?>
<div id="importCsvSteps">
    <h1><?php echo Yii::t('importcsvModule.importcsv', 'Import'); ?> CSV</h1>

    <strong><?php echo Yii::t('importcsvModule.importcsv', 'File'); ?> :</strong> <span
    id="importCsvForFile">&nbsp;</span><br/>
    <strong><?php echo Yii::t('importcsvModule.importcsv', 'Delimiter'); ?> :</strong> <span id="importCsvForDelimiter">&nbsp;</span><br/>
    <strong><?php echo Yii::t('importcsvModule.importcsv', 'Table'); ?> :</strong> <span
    id="importCsvForTable">&nbsp;</span><br/><br/>

    <?php echo CHtml::beginForm('', 'post', array('enctype'=> 'multipart/form-data')); ?>
    <?php echo CHtml::hiddenField("fileName", ""); ?>
    <?php echo CHtml::hiddenField("thirdStep", "0"); ?>

    <div id="importCsvFirstStep">
        <div id="importCsvFirstStepResult">
            &nbsp;
        </div>
        <?php  echo CHtml::button(Yii::t('importcsvModule.importcsv', 'Select CSV File'), array("id"=> "importStep1")); ?>
    </div>
    <div id="importCsvSecondStep">
        <div id="importCsvSecondStepResult">
            &nbsp;
        </div>
        <strong><?php echo Yii::t('importcsvModule.importcsv', 'Delimiter'); ?></strong> <span
        class="require">*</span><br/>
        <?php echo CHtml::textField("delimiter", $delimiter); ?>
        <br/><br/>

        <strong><?php echo Yii::t('importcsvModule.importcsv', 'Table'); ?></strong> <span class="require">*</span><br/>
        <?php echo CHtml::dropDownList('table', '', $tablesArray);?><br/><br/>

        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('importcsvModule.importcsv', 'Next'), '', array(
            'update' => '#importCsvSecondStepResult',
        ));
        ?>
    </div>
    <?php echo CHtml::endForm(); ?>

    <div id="importCsvThirdStep">
        <?php echo CHtml::beginForm('', 'post'); ?>
        <?php echo CHtml::hiddenField("thirdStep", "1"); ?>
        <?php echo CHtml::hiddenField("thirdDelimiter", ""); ?>
        <?php echo CHtml::hiddenField("thirdTable", ""); ?>
        <?php echo CHtml::hiddenField("thirdFile", ""); ?>
        <div id="importCsvThirdStepResult">
            &nbsp;
        </div>
        <div id="importCsvThirdStepPolesAndForm">
            <div id="importCsvThirdStepPoles">&nbsp;</div>
            <br/>
            <?php
            echo CHtml::ajaxSubmitButton(Yii::t('importcsvModule.importcsv', 'Import'), '', array(
                'update' => '#importCsvThirdStepResult',
            ));
            ?>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
    <br/>
    <span
        id="importCsvBread1">&laquo; <?php echo CHtml::link(Yii::t('importcsvModule.importcsv', 'Start over'), "/importcsv");?></span>
    <span id="importCsvBread2"> &laquo; <a href="javascript:void(0)"
                                           id="importCsvA2"><?php echo Yii::t('importcsvModule.importcsv', 'Delimiter') . " " . Yii::t('importcsvModule.importcsv', 'and') . " " . Yii::t('importcsvModule.importcsv', 'Table');?></a></span>
</div>