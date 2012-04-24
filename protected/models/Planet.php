<?php
/**
 * MANY_MANY  Ajax Crud Admnistration
 * Planet Model
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

Yii::import('application.models._base.BasePlanet');

class Planet extends BasePlanet {

    //paging size for all products
    const PAGING_SIZE_ALL = 10;
    public $planet_image;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function relations() {
        return array(
            'groups'         => array(self::MANY_MANY, 'Group', 'planet_group(planet_id, group_id)'),
            'satellites'     => array(self::HAS_MANY, 'Satellite', 'parentPlanetID'),
            'satelliteCount' => array(self::STAT, 'Satellite', 'parentPlanetID'),
        );
    }

    public function rules() {
        return array(
            array('planet_image', 'file',
                'types'      => 'png, gif, jpg',
                'allowEmpty' => true),
            array('name, planetGSM, address', 'required'),
            array('NrSatellites, extra3, extra4', 'numerical',
                'integerOnly'=> true),
            array('name', 'length',
                'max'=> 100),
            array('planetGSM', 'length',
                'max'=> 20),
            array('address, extra1, extra2', 'length',
                'max'=> 45),
            array('installDate, updateDate', 'safe'),
            array('NrSatellites, installDate, updateDate, extra1, extra2, extra3, extra4', 'default',
                'setOnEmpty' => true,
                'value'      => null),
            array('id, name, planetGSM, address, NrSatellites, installDate, updateDate, extra1, extra2, extra3, extra4', 'safe',
                'on'=> 'search'),
        );
    }

    public function searchCriteria() {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.planetGSM', $this->planetGSM, true);
        $criteria->compare('t.address', $this->address, true);
        $criteria->compare('t.NrSatellites', $this->NrSatellites);
        $criteria->compare('t.installDate', $this->installDate, true);
        $criteria->compare('t.updateDate', $this->updateDate, true);
        $criteria->compare('t.extra1', $this->extra1, true);
        $criteria->compare('t.extra2', $this->extra2, true);
        $criteria->compare('t.extra3', $this->extra3);
        $criteria->compare('t.extra4', $this->extra4);
        return $criteria;
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('planetGSM', $this->planetGSM, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('NrSatellites', $this->NrSatellites);
        $criteria->compare('installDate', $this->installDate, true);
        $criteria->compare('updateDate', $this->updateDate, true);
        $criteria->compare('extra1', $this->extra1, true);
        $criteria->compare('extra2', $this->extra2, true);
        $criteria->compare('extra3', $this->extra3);
        $criteria->compare('extra4', $this->extra4);
        return new CActiveDataProvider($this, array(
            'criteria'   => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGING_SIZE_ALL,
            )
        ));
    }

    public function behaviors() {
        return array(
            'planetImgBehavior' => array(
                'class'                 => 'ImageARBehavior',
                'attribute'             => 'planet_image', // this must exist
                'extension'             => 'png, gif, jpg', // possible extensions, comma separated
                'prefix'                => 'img_',
                'relativeWebRootFolder' => 'img/product', // this folder must exist
                'formats'               => array(
                    'thumb'  => array(
                        'suffix'  => '_thumb',
                        'process' => array('resize' => array(100, 100)),
                    ),
                    'small'  => array(
                        'suffix'  => '_small',
                        'process' => array('resize' => array(50, 50)),
                    ),
                    'large'  => array(
                        'suffix' => '_large',
                    ),
                    'normal' => array(
                        'process' => array('resize' => array(200, 200)),
                    ),
                ),
                'defaultName'           => 'default', // when no file is associated, this one is used by getFileUrl
            )
        );
    }
}