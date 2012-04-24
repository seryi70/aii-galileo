<?php
/**
 * MANY_MANY  Ajax Crud Administration
 * Group Model
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

Yii::import('application.models._base.BaseGroup');

class Group extends BaseGroup {

    //the id of the jstree div
    const ADMIN_TREE_CONTAINER_ID = 'group_admin_tree';

    //optional image used with fileImagebehavior
    public $group_image;


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    //IMPORTANT:Remove all rules associated with  root, lft, rgt, level  properties.
    public function rules() {
        return array(
            array('group_image', 'file',
                'types'      => 'png, gif, jpg',
                'allowEmpty' => true),
            array('name', 'required'),
            array('name', 'length',
                'max'=> 50),
            array('description', 'length',
                'max'=> 200),
            array('description', 'default',
                'setOnEmpty' => true,
                'value'      => null),
            array('id, name, description,', 'safe',
                'on'=> 'search'),
        );
    }

    public function behaviors() {
        return array(
            'NestedSetBehavior'=> array(
                'class'         => 'ext.nestedBehavior.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute'=> 'rgt',
                'levelAttribute'=> 'level',
                'hasManyRoots'  => true
            ),
            //optional if you want an image associated with this model.FileImageBehavior extension is used.
            'groupImgBehavior' => array(
                'class'                 => 'ImageARBehavior',
                'attribute'             => 'group_image', // this must exist
                'extension'             => 'png, gif, jpg', // possible extensions, comma separated
                'prefix'                => 'img_',
                'relativeWebRootFolder' => 'img/category', // this folder must exist

                # 'forceExt' => png, // this is the default, every saved image will be a png one.
                # Set to null if you want to keep the original format

                //	'useImageMagick' => '/usr/bin', # I want to use imagemagick instead of GD, and
                # it is located in /usr/bin on my computer.

                // this will define formats for the image.
                // The format 'normal' always exist. This is the default format, by default no
                // suffix or no processing is enabled.
                'formats'               => array(
                    // create a thumbnail grayscale format
                    'thumb'  => array(
                        'suffix'  => '_thumb',
                        'process' => array('resize' => array(60, 60)),
                    ),
                    // create a large one (in fact, no resize is applied)
                    'large'  => array(
                        'suffix' => '_large',
                    ),
                    // and override the default :
                    'normal' => array(
                        'process' => array('resize' => array(200, 200)),
                    ),
                ),

                'defaultName'           => 'default', // when no file is associated, this one is used by getFileUrl
                // defaultName need to exist in the relativeWebRootFolder path, and prefixed by prefix,
                // and with one of the possible extensions. if multiple formats are used, a default file must exist
                // for each format. Name is constructed like this :
                //     {prefix}{name of the default file}{suffix}{one of the extension}
            )
        );
    }

    //prints an unordered list of anchor tags  for the model records,used in jstree.
    public static function printULTree() {
        $categories = Group::model()->findAll(array('order'=> 'root,lft'));
        $level = 0;

        foreach ($categories as $n=> $category)
        {

            if ($category->level == $level)
                echo CHtml::closeTag('li') . "\n";
            else if ($category->level > $level)
                echo CHtml::openTag('ul') . "\n";
            else
            {
                echo CHtml::closeTag('li') . "\n";

                for ($i = $level - $category->level; $i; $i--)
                {
                    echo CHtml::closeTag('ul') . "\n";
                    echo CHtml::closeTag('li') . "\n";
                }
            }
            //CS TODO:
            $thumb_url = $category->groupImgBehavior->getFileUrl('thumb');
            if (!empty($thumb_url))
                $url = $thumb_url; else $url = Yii::app()->baseUrl . '/img/placeholder_70.jpg';

            echo CHtml::openTag('li', array('id' => 'node_' . $category->id,
                                            'rel'=> $category->name));
            echo ('<a  ' . ' id=' . $category->id . '  class=category_name' . ' rel=' . $url . '  href="#"' . '>');
            echo CHtml::encode($category->name);
            echo CHtml::closeTag('a');
            $level = $category->level;
        }
        for ($i = $level; $i; $i--)
        {
            echo CHtml::closeTag('li') . "\n";
            echo CHtml::closeTag('ul') . "\n";
        }

    }

    public static function printULTree_noAnchors() {
        $categories = Group::model()->findAll(array('order'=> 'lft'));
        $level = 0;

        foreach ($categories as $n=> $category)
        {
            if ($category->level == $level)
                echo CHtml::closeTag('li') . "\n";
            else if ($category->level > $level)
                echo CHtml::openTag('ul') . "\n";
            else //if $category->level<$level
            {
                echo CHtml::closeTag('li') . "\n";

                for ($i = $level - $category->level; $i; $i--) {
                    echo CHtml::closeTag('ul') . "\n";
                    echo CHtml::closeTag('li') . "\n";
                }
            }

            echo CHtml::openTag('li');
            echo CHtml::encode($category->name);
            $level = $category->level;
        }

        for ($i = $level; $i; $i--) {
            echo CHtml::closeTag('li') . "\n";
            echo CHtml::closeTag('ul') . "\n";
        }

    }

}