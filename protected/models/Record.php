<?php

Yii::import('application.models._base.BaseRecord');

class Record extends BaseRecord
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}