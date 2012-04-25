<ul>
	<li><?php if(Yii::app()->user->checkAccess('admin.UserAdministrating')) echo CHtml::link('Manage Users',array('srbac/authitem/frontpage')); ?></li>
	<li><?php if(Yii::app()->user->checkAccess('GroupAdministrating')) echo CHtml::link('Manage PlanetGroups',array('group/admin_grid')); ?></li>
	<li><?php if(Yii::app()->user->checkAccess('SatelliteAdministrating')) echo CHtml::link('Manage Satellites',array('satellite/admin')); ?></li>
	<li><?php if(Yii::app()->user->checkAccess('RecordViewing')) echo CHtml::link('Analyse Data',array('record/index')); ?></li>
	<li><?php echo CHtml::link('Logout',array('site/logout')); ?></li>
</ul>
