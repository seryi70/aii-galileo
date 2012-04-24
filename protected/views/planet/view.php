<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  view.php   detailed view for planet * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->

<?php
/**If you want you can use $img_url for the image source url,so that you can fall back to a placeholder image,if no image exists for model.*/
$large_src = $model->planetImgBehavior->getFileUrl('large');
$img_url = (isset($large_src)) ? $large_src :
    Yii::app()->baseUrl . '/img/placeholder_100.jpg';

?>

<div class="details_view">
    <div class="info_wrapper">
        <h1><?php echo Yii::t('app', 'View') . ' ' . GxHtml::encode($model->label()) . ' ' . GxHtml::encode(GxHtml::valueEx($model)); ?></h1>
        <?php $this->widget('zii.widgets.CDetailView', array(
        'data'       => $model,
        'attributes' => array(
            'id',
            'name',
            'planetGSM',
            'address',
            'NrSatellites',
            'installDate',
            'updateDate',
            'extra1',
            'extra2',
            'extra3',
            'extra4',
        ),
    )); ?>

        <h2><?php echo GxHtml::encode($model->getRelationLabel('groups')); ?></h2>
        <?php
        echo GxHtml::openTag('ul');
        foreach ($model->groups as $relatedModel) {
            echo GxHtml::openTag('li');
            echo   GxHtml::encode(GxHtml::valueEx($relatedModel));
            echo GxHtml::closeTag('li');
        }
        echo GxHtml::closeTag('ul');
        ?><h2><?php echo GxHtml::encode($model->getRelationLabel('satellites')); ?></h2>
        <?php
        echo GxHtml::openTag('ul');
        foreach ($model->satellites as $relatedModel) {
            echo GxHtml::openTag('li');
            echo   GxHtml::encode(GxHtml::valueEx($relatedModel));
            echo GxHtml::closeTag('li');
        }
        echo GxHtml::closeTag('ul');
        ?>       </div>

</div>