<?php

Yii::import('application.models._base.BaseSatellite');

class Satellite extends BaseSatellite
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	public function relations() {
		return array(
			'records' => array(self::HAS_MANY, 'Record', 'satelliteID'),
			'parentPlanet' => array(self::BELONGS_TO, 'Planet', 'parentPlanetID'),
			'recordCount' => array(self::STAT, 'Record', 'satelliteID'),
		);
	}
}