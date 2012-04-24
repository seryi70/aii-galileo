<?php
$this->breadcrumbs = array(
    'Satellites'=> array('index'),
    $model->name,
);
?>
<!--<h1>View Satellite #<?php echo $model->id; ?></h1>-->
<h1><?php echo Yii::t('app', 'View') . ' ' . GxHtml::encode($model->label()) . ' ' . GxHtml::encode(GxHtml::valueEx($model)); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'      => $model,
    'attributes'=> array(
        'id',
        'name',
        'active',
        'parentPlanetID',
        'address',
        'installDate',
        'updateDate',
        'deactivateDate',
        'error',
        'extra1',
        'extra2',
        'extra3',
        'extra4',
    ),
)); ?>
