<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'firstName',
		'lastName',
		'username',
//		'password',
		'email',
	),
)); ?>
