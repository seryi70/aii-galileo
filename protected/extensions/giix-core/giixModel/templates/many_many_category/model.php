<?php
/**
 * This is the template for generating the model class of a specified table.
 * In addition to the default model Code, this adds the CSaveRelationsBehavior
 * to the model class definition.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 * - $representingColumn: the name of the representing column for the table (string) or
 *   the names of the representing columns (array)
 */
?>
<?php echo "<?php\n"; ?>
/**
 * MANY_MANY  Ajax Crud Administration
 * <?php echo $modelClass; ?> Model
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

Yii::import('<?php echo "{$this->baseModelPath}.{$this->baseModelClass}"; ?>');

class <?php echo $modelClass; ?> extends <?php echo $this->baseModelClass."\n"; ?>
{

    //the id of the jstree div
   const ADMIN_TREE_CONTAINER_ID='<?php echo  $this->class2var($modelClass);?>_admin_tree';

   //optional image used with fileImagebehavior
    public $<?php echo  $this->class2var($modelClass);?>_image;


	public static function model($className=__CLASS__) {
		return parent::model($className);
	}


    //IMPORTANT:Remove all rules associated with  root, lft, rgt, level  properties.
        	public function rules() {
		return array(
     array('<?php echo $this->class2var($this->modelClass);?>_image', 'file', 'types' => 'png, gif, jpg', 'allowEmpty' => true),
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

         public function behaviors()
{
    return array(
        'NestedSetBehavior'=>array(
            'class'=>'ext.nestedBehavior.NestedSetBehavior',
            'leftAttribute'=>'lft',
            'rightAttribute'=>'rgt',
            'levelAttribute'=>'level',
            'hasManyRoots'=>true
            ),
        //optional if you want an image associated with this model.FileImageBehavior extension is used.
       '<?php echo  $this->class2var($modelClass);?>ImgBehavior' => array(
				'class' => 'ImageARBehavior',
				'attribute' => '<?php echo  $this->class2var($modelClass);?>_image', // this must exist
				'extension' => 'png, gif, jpg', // possible extensions, comma separated
				'prefix' => 'img_',
				'relativeWebRootFolder' => 'img/category', // this folder must exist

				# 'forceExt' => png, // this is the default, every saved image will be a png one.
				# Set to null if you want to keep the original format

			//	'useImageMagick' => '/usr/bin', # I want to use imagemagick instead of GD, and
				# it is located in /usr/bin on my computer.

				// this will define formats for the image.
				// The format 'normal' always exist. This is the default format, by default no
				// suffix or no processing is enabled.
				'formats' => array(
					// create a thumbnail grayscale format
					'thumb' => array(
						'suffix' => '_thumb',
						'process' => array('resize' => array(60, 60)),
					),
					// create a large one (in fact, no resize is applied)
					'large' => array(
						'suffix' => '_large',
					),
					// and override the default :
					'normal' => array(
						'process' => array('resize' => array(200, 200)),
					),
				),

				'defaultName' => 'default', // when no file is associated, this one is used by getFileUrl
				// defaultName need to exist in the relativeWebRootFolder path, and prefixed by prefix,
				// and with one of the possible extensions. if multiple formats are used, a default file must exist
				// for each format. Name is constructed like this :
				//     {prefix}{name of the default file}{suffix}{one of the extension}
			)
    );
}

   //prints an unordered list of anchor tags  for the model records,used in jstree.
    public static  function printULTree(){
       $categories=<?php echo $modelClass; ?>::model()->findAll(array('order'=>'root,lft'));
       $level=0;

  foreach($categories as $n=>$category)
  {

      if($category->level==$level)
          echo CHtml::closeTag('li')."\n";
      else if($category->level>$level)
          echo CHtml::openTag('ul')."\n";
      else
      {
          echo CHtml::closeTag('li')."\n";

          for($i=$level-$category->level;$i;$i--)
          {
              echo CHtml::closeTag('ul')."\n";
              echo CHtml::closeTag('li')."\n";
          }
      }
$thumb_url=$category-><?php echo  $this->class2var($modelClass);?>ImgBehavior->getFileUrl('thumb');
if  (!empty($thumb_url))
$url=$thumb_url;else $url=Yii::app()->baseUrl.'/img/placeholder_70.jpg';

      echo CHtml::openTag('li',array('id'=>'node_'.$category->id,'rel'=>$category->name));
     echo ( '<a  '. ' id='.$category->id.'  class=category_name' .' rel=' . $url .  '  href="#"'.   '>'  );
      echo CHtml::encode($category->name);
        echo CHtml::closeTag('a');
      $level=$category->level;
  }
  for($i=$level;$i;$i--)
  {
      echo CHtml::closeTag('li')."\n";
      echo CHtml::closeTag('ul')."\n";
  }

  }

  public static  function printULTree_noAnchors(){
      $categories=<?php echo $modelClass; ?>::model()->findAll(array('order'=>'lft'));
      $level=0;

  foreach($categories as $n=>$category)
  {
      if($category->level == $level)
          echo CHtml::closeTag('li')."\n";
      else if ($category->level > $level)
          echo CHtml::openTag('ul')."\n";
      else         //if $category->level<$level
      {
          echo CHtml::closeTag('li')."\n";

          for ($i = $level - $category->level; $i; $i--) {
                      echo CHtml::closeTag('ul') . "\n";
                      echo CHtml::closeTag('li') . "\n";
                  }
      }

      echo CHtml::openTag('li');
      echo CHtml::encode($category->name);
      $level=$category->level;
  }

  for ($i = $level; $i; $i--) {
              echo CHtml::closeTag('li') . "\n";
              echo CHtml::closeTag('ul') . "\n";
          }

  }

}