<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/**
* MANY_MANY  Ajax Crud Admnistration Demo
* <?php echo $this->controllerClass; ?>
* InfoWebSphere {@link http://libkal.gr/infowebsphere}
* @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?> {

<?php 
	$authpath = 'ext.giix-core.giixCrud.templates.default.auth.';
	Yii::app()->controller->renderPartial($authpath . $this->authtype);
    $related_this_Class = $this->getRelations($this->modelClass);
  $relatedModelClass = $related_this_Class[0][3];
  $relationName = $related_this_Class[0][0];

$related_Related_Class = $this->getRelations($relatedModelClass);
 $relatedRelatedModelClass = $related_Related_Class[0][3];
$relatedRelationName = $related_Related_Class[0][0];
?>

    public function actionCreate()
    {
      $model = new <?php echo $this->modelClass; ?>;
      //	$this->performAjaxValidation($model, 'product-form');
      if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
          $model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);
          $relatedData = array(
              '<?php echo$relationName; ?>' => $_POST['<?php echo $this->modelClass; ?>']['<?php echo $relationName; ?>'] === '' ? null : $_POST['<?php echo $this->modelClass; ?>']['<?php echo$relationName; ?>'],
          );
          if ($model->saveWithRelated($relatedData)) { //if model was saved
              echo '<textarea>' . json_encode(array('success' => true,
    'id' => $model->primaryKey)
    ) . '</textarea>';
              Yii::app()->end();
          } //else if model was not saved
          else {
              echo '<textarea>' . json_encode(array('success' => false)) . '</textarea>';
              Yii::app()->end();
          }
      }
    }

    public function actionUpdate()
    {
        // $this->performAjaxValidation($model, '<?php echo  $this->class2var($this->modelClass);?>-form');
        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model = $this->loadModel($_POST['<?php echo $this->modelClass; ?>']['update_id'], '<?php echo $this->modelClass; ?>');
            $model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);
            $relatedData = array(
                '<?php echo$relationName; ?>' => $_POST['<?php echo $this->modelClass; ?>']['<?php echo$relationName; ?>'] === '' ? null : $_POST['<?php echo $this->modelClass; ?>']['<?php echo$relationName; ?>'],
            );
            if ($model->saveWithRelated($relatedData)) {
                echo '<textarea>' . json_encode(array('success' => true,
    'id' => $model->primaryKey,
    'thumb_url' => $model-><?php echo  $this->class2var($this->modelClass);?>ImgBehavior->getFileUrl('thumb')
    )
    ) . '</textarea>';
                Yii::app()->end();
            } else {
                echo '<textarea>' . json_encode(array('success' => false)) . '</textarea>';
                Yii::app()->end();
            }
        } //isset POST
    }

    public function actionDelete()
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            if ($this->loadModel($_POST['id'], '<?php echo $this->modelClass; ?>')->delete()) {
                echo json_encode(array('success' => true));
                exit;
            } else
            {
                echo json_encode(array('success' => false));
                exit;
            }
        } else
            throw new CHttpException(400, Yii::t('app', 'Your request is invalid.'));
    }

/**
	 * Returns options for the related model multiple select
	 * @param string $model
	 * @return  string options for relate model  multiple select
	 * @since 1.0
	 */
     public function related_opts($model){
       $relatedPKs=<?php echo $relatedModelClass; ?>::extractPkValue($model-><?php echo $relationName?>);
       $options='';
       $categories=GxHtml::listDataEx(<?php echo $relatedModelClass; ?>::model()->findAllAttributes(null, true));
       foreach ($categories as $value=>$text){
               if(!$model->isNewRecord) {
                in_array($value,$relatedPKs)?
           $options .= '<option selected="selected" value='.$value.'>'.$text.'</option>\n':
           $options .= '<option value='.$value.'>'.$text.'</option>\n';
               }else{
           $options.='<option value='.$value.'>'.$text.'</option>\n';
             }
       }
    echo  $options;
    }

      public function actionReturnProductForm(){
         //don't reload these scripts or they will mess up the page
         //yiiactiveform.js still needs to be loaded that's why we don't use
         // Yii::app()->clientScript->scriptMap['*.js'] = false;
         $cs=Yii::app()->clientScript;
         $cs->scriptMap=array(
          'jquery.min.js'=>false,
         'jquery.js'=>false,
         'jquery.fancybox.js'=>false,
        );

          //if we are creating a new product
          if($_POST['update']!='true') {
          $model=new <?php echo $this->modelClass; ?>;
           //else load the model to update
          }else{
              $model=$this->loadModel($_POST['update_id'],<?php echo $this->modelClass; ?>);
          }
        $this->renderPartial('_form', array('model'=>$model ), false, true);
      }

    //renders the details view inside fancybox
    public function actionReturnView(){
       //don't reload these scripts or they will mess up the page
       //yiiactiveform.js still needs to be loaded that's why we don't use
       // Yii::app()->clientScript->scriptMap['*.js'] = false;
       $cs=Yii::app()->clientScript;
       $cs->scriptMap=array(
        'jquery.min.js'=>false,
       'jquery.js'=>false,
       'jquery.fancybox.js'=>false,
       );
       $model=$this->loadModel($_POST['id'],<?php echo $this->modelClass; ?>);
       $this->renderPartial('view', array('model'=>$model),  false, true);
    }
}