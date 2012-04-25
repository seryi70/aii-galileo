<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  view.php   detailed view for <?php echo $this->class2var($this->modelClass);?>
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->

<?php echo '<?php '; ?>

/**If you want you can use $img_url for the image source url,so that you can fall back to a placeholder image,if no image exists for model.*/
$large_src = $model-><?php echo $this->class2var($this->modelClass);?>ImgBehavior->getFileUrl('large');
$img_url = (isset($large_src)) ? $large_src :Yii::app()->baseUrl . '/img/placeholder_100.jpg';

<?php echo '?>'; ?>

<div class="details_view">
   <div class="info_wrapper">

<h1><?php echo '<?php'; ?> echo Yii::t('app', 'View') . ' ' . GxHtml::encode($model->label()) . ' ' . GxHtml::encode(GxHtml::valueEx($model)); ?></h1>
<?php echo '<?php'; ?> $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
<?php
foreach ($this->tableSchema->columns as $column)
		echo $this->generateDetailViewAttribute($this->modelClass, $column) . ",\n";
?>
	),
)); ?>

<?php foreach (GxActiveRecord::model($this->modelClass)->relations() as $relationName => $relation): ?>
<?php if ($relation[0] == GxActiveRecord::HAS_MANY || $relation[0] == GxActiveRecord::MANY_MANY): ?>
<h2><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relationName; ?>')); ?></h2>
<?php echo "<?php\n"; ?>
	echo GxHtml::openTag('ul');
	foreach($model-><?php echo $relationName; ?> as $relatedModel) {
		echo GxHtml::openTag('li');
		echo GxHtml::encode(GxHtml::valueEx($relatedModel));
		echo GxHtml::closeTag('li');
	}
	echo GxHtml::closeTag('ul');
<?php echo '?>'; ?>
<?php endif; ?>
<?php endforeach; ?>
       </div>
    </div>