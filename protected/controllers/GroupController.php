<?php
/**
 * MANY_MANY  Ajax Crud Admnistration Demo
 * GroupController * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

class GroupController extends GxController {

  /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public $layout='//layouts/column2';
    //paging size per category
     const PAGING_SIZE_CAT=30;

               public function   init() {
                $this->registerAssets();
                 parent::init();
    }

    private function registerAssets()
    {

        Yii::app()->clientScript->registerCoreScript('jquery');

        //Jstree
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/jstree/jquery.jstree.js', CClientScript::POS_HEAD);

        //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
        // http://fancyapps.com/fancybox/#license
        // FancyBox
        //If you use the old fancybox plugin you'll have to do a Find/Replace in your IDE   to replace all "beforeClose"   with "onClosed".(callback function).
        /*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/plugins/fancybox/jquery.fancybox-1.3.4.css','screen');*/
        // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');

        //JQueryUI (for delete confirmation  dialog)
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css', 'screen');

        ///JSON2JS
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/json2/json2.js');

        //chosen,for multi selection in forms.
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/chosen/chosen.jquery.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/chosen/chosen.css');

        //jqueryform js
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/ajaxform/ajax_binding.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/ajaxform/client_val_form.css', 'screen');

        //demo css.You 'll probably want to modify this to suit your needs.
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/many_many.css', 'screen');

    }


        /**
	 * Returns options for the related model multiple select
	 * @param string $model
	 * @return  string options for relate model  multiple select
	 * @since 1.0
	 */
    public function related_opts($model){
        $relatedPKs= Planet::extractPkValue($model->planets);
        $options='';
       $products=GxHtml::listDataEx(Planet::model()->findAllAttributes(null, true));
       foreach ($products as $value=>$text){
                     if(!$model->isNewRecord) {
                            in_array($value,$relatedPKs)?
           $options .= '<option selected="selected" value='.$value.'>'.$text.'</option>\n':
           $options .= '<option  value='.$value.'>'.$text.'</option>\n';
                     }else{
           $options.='<option  value='.$value.'>'.$text.'</option>\n';
                     }
       }
          echo  $options;
    }


/**
   *  Administration Page with ListView
* @return  Renders Administration Page with ListView
   * @since 1.0
   */
public function actionAdmin_list()
{
  $baseUrl = Yii::app()->baseUrl;
  //prepare dataproviders
  $data = $this->prepare_dataproviders();
  $this->render('admin_list', array(
                                   'dataProvider' => $data['cat_provider'],
                                   'prod_dataProvider' => (isset($_GET['cat_id']) && $_GET['cat_id'] != 'all') ?
                                                                                  $data['prod_provider'] :
                                                                                  $data['model']->search(),
                                   'category_id' => isset($_GET['cat_id']) ? $_GET['cat_id'] : 'all',
                                   'baseUrl' => $baseUrl,
                                   'open_nodes' => $data['open_nodes'],
                              ));
}

   /**
	 *  Administration Page with GridView
     * @return  Renders Administration Page with GridView
	 * @since 1.0
	 */
    public function actionAdmin_grid()
    {
        $baseUrl = Yii::app()->baseUrl;
        //prepare dataproviders  for Category and Product models
        $data = $this->prepare_dataproviders();
        $this->render('admin_grid', array(
                                         'dataProvider' => $data['cat_provider'],
                                         'prod_dataProvider' => (isset($_GET['cat_id']) && $_GET['cat_id'] != 'all')?
                                                                                        $data['prod_provider'] :
                                                                                        $data['model']->search(),
                                         'category_id' => isset($_GET['cat_id']) ? $_GET['cat_id'] : 'all',
                                         'baseUrl' => $baseUrl,
                                         'open_nodes' => $data['open_nodes'],
                                         'model' => $data['model']
                                    ));
    }

   /**
	 *  Frontend Page with ListView (No administration)
     * @return  Renders Frontend Page with ListView (No administration)
	 * @since 1.0
	 */
    public function actionIndex_list() {

        $baseUrl=Yii::app()->baseUrl;
   //prepare dataproviders  for Category and Product models
    $data=$this->prepare_dataproviders();
		$this->render('index_list',array(
			                      'dataProvider'=>$data['cat_provider'],
                                   'prod_dataProvider'=>(isset($_GET['cat_id']) && $_GET['cat_id']!='all')?$data['prod_provider']:$data['model']->search(),
                                      'category_id'=>isset($_GET['cat_id'])?$_GET['cat_id']:'all',
                                      'baseUrl'=> $baseUrl,
                                      'open_nodes'=> $data['open_nodes']
		                      ));
	}


     /**
	 *  Frontend Page with GridView (No administration)
     * @return  Renders Frontend Page with GridView (No administration)
	 * @since 1.0
	 */
    	public function actionIndex_grid() {

        $baseUrl=Yii::app()->baseUrl;
   //prepare dataproviders  for Category and Product models
    $data=$this->prepare_dataproviders();
		$this->render('index_grid',array(
			                      'dataProvider'=>$data['cat_provider'],
                                   'prod_dataProvider'=>(isset($_GET['cat_id']) && $_GET['cat_id']!='all')?$data['prod_provider']:$data['model']->search(),
                                      'category_id'=>isset($_GET['cat_id'])?$_GET['cat_id']:'all',
                                      'baseUrl'=> $baseUrl,
                                      'open_nodes'=> $data['open_nodes'],
                                      'model' => $data['model']
		                      ));
	}

         /**
	 *  Prepares dataproviders for models and jstree open nodes.
     * @return  array  Contains the dataproviders for the models,open nodes for the tree and the model to search for.
	 * @since 1.0
	 */
    private function prepare_dataproviders()
    {

        //create an array open_nodes with the ids of the nodes that we want to be initially open
        //when the tree is loaded.Modify this to suit your needs.Here,we open all nodes on load.
        $categories = Group::model()->findAll(array('order' => 'lft'));
        $identifiers = array();
        foreach ($categories as $n => $category)
        {
            $identifiers[] = "'" . 'node_' . $category->id . "'";
        }

        $open_nodes = implode(',', $identifiers);

        $cat_dataProvider = new CActiveDataProvider('Group');

        $prod_criteria = new CDbCriteria;
        $prod_criteria->with = array('groups' => array(
            'on' => 'groups.id=:cat_id',
            'together' => true,
            'joinType' => 'INNER JOIN',
            'params' => array(':cat_id' => $_GET['cat_id'])
        ));

        //for search
        $model = new Planet('search');
        $model->unsetAttributes();
        if (isset($_GET['Planet'])) { //if searching
            $model->setAttributes($_GET['Planet']);
            $search_criteria = $model->searchCriteria();
            $prod_criteria->mergeWith($search_criteria);
        }

        $prod_dataProvider = new CActiveDataProvider('Planet', array(
                                                                     'criteria' => $prod_criteria,
                                                                     'pagination' => array(
                                                                         'pageSize' => self::PAGING_SIZE_CAT,
                                                                     ),
                                                                ));
        return (
        array(
            'cat_provider' => $cat_dataProvider,
            'prod_provider' => $prod_dataProvider,
            'open_nodes' => $open_nodes,
            'model' => $model
        )

        );

    }



/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */

	public function loadModel($id)
	{
		$model=Group::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionFetchTree(){
          Group::printULTree();
      }


   public function actionRename(){

           $new_name=$_POST['new_name'];
           $id=$_POST['id'];
           $renamed_cat=$this->loadModel($id);
           $renamed_cat->name= $new_name;
          if ($renamed_cat->saveNode()){
              echo json_encode (array('success'=>true));
              exit;
      }else{
                  echo json_encode (array('success'=>false));
                    exit;
                }
      }

       public function actionRemove(){
                  $id=$_POST['id'];
                 $deleted_cat=$this->loadModel($id);
                if ($deleted_cat->deleteNode() ){
               echo json_encode (array('success'=>true));
               exit;
                }else{
                  echo json_encode (array('success'=>false,'deleted_id'=>$id));
                    exit;
                }
      }

      //renders the create or update form inside fancybox
    public function actionReturnForm()
    {
        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs = Yii::app()->clientScript;
        $cs->scriptMap = array(
            'jquery.min.js' => false,
            'jquery.js' => false,
            'jquery.form.js' => false,
            'ajax_form_bind.js' => false,
            'jquery.fancybox.js' => false,
            'jquery.jstree.js' => false,
            'jquery-ui-1.8.12.custom.min.js' => false,
            'json2.js' => false,
        );

        //Figure out if we are updating a Model or creating a new one.
        if (isset($_POST['update_id'])) $model = $this->loadModel($_POST['update_id']); else $model = new Group;
        $this->renderPartial('_form', array('model' => $model,
                                           'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : '',
                                           'newname' => !empty($_POST['newname']) ? $_POST['newname'] : '',
                                           'create_root' => !empty($_POST['create_root']) ? $_POST['create_root'] : ''
                                      ),
                             false, true);
    }


        //renders the  details view inside fancybox
    public function actionReturnView()
    {

        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs = Yii::app()->clientScript;
        $cs->scriptMap = array(
            'jquery.min.js' => false,
            'jquery.js' => false,
             'jquery.fancybox.js'=> false,
            'jquery.jstree.js' => false,
            'jquery-ui-1.8.12.custom.min.js' => false,
            'json2.js' => false,
            'jquery.form.js' => false,
            'ajax_form_bind.js' => false,
            'chosen.jquery.js' => false,

        );

        $model = $this->loadModel($_POST['id']);

        $this->renderPartial('view', array(
                                          'model' => $model,
                                     ),
                             false, true);

    }


     public function actionCreateRoot()
    {
        if (isset($_POST['Group'])) {
            $new_root = new Group;
            $new_root->setAttributes($_POST['Group']);
            $relatedData = array(
                'planets' => $_POST['Group']['planets'] === '' ? null : $_POST['Group']['planets'],
            );

            if ($new_root->saveNodeWithRelated($relatedData)) {
                echo json_encode(array('success' => true,
                                      'id' => $new_root->primaryKey)
                );
                exit;
            } else
            {
                echo json_encode(array('success' => false));
                exit;
            }
        }

    }


    public function actionCreate()
    {
        if (isset($_POST['Group'])) {
            $model = new Group;
            //set the submitted values
            $model->attributes = $_POST['Group'];
            $parent = $this->loadModel($_POST['parent_id']);

            //save and return the JSON result to provide feedback.
            if ($model->appendToWithRelated(array('planets' => $_POST['Group']['planets']), $parent)) {
                echo json_encode(array('success' => true,
                                      'id' => $model->primaryKey
                                 )
                );
                exit;
            } else
            {
                echo json_encode(array('success' => false));
                exit;
            }
        }

    }


    public function actionUpdate()
    {

        if (isset($_POST['Group'])) {

            $model = $this->loadModel($_POST['update_id']);
            $model->attributes = $_POST['Group'];

            if ($model->saveNodeWithRelated(array('planets' => $_POST['Group']['planets']), false)) {
                echo json_encode(array('success' => true,
                                      'update_id' => $_POST['update_id'],
                                      'updated_name' => $model->name,
                                      'updated_img' => $model->groupImgBehavior->getFileUrl('thumb')));
            }
            else echo json_encode(array('success' => false));
        }

    }


//maps all move and copy actions on jstree to  serverside database operations.
    public function actionMoveCopy()
    {

        $moved_node_id = $_POST['moved_node'];
        $new_parent_id = $_POST['new_parent'];
        $new_parent_root_id = $_POST['new_parent_root'];
        $previous_node_id = $_POST['previous_node'];
        $next_node_id = $_POST['next_node'];
        $copy = $_POST['copy'];

        //the following is additional info about the operation provided by
        // the jstree.It's there if you need it.See documentation for jstree.

        //  $old_parent_id=$_POST['old_parent'];
        //$pos=$_POST['pos'];
        //  $copied_node_id=$_POST['copied_node'];
        //  $replaced_node_id=$_POST['replaced_node'];

        //the  moved,copied  node
        $moved_node = $this->loadModel($moved_node_id);

        //if we are not moving as a new root...
        if ($new_parent_root_id != 'root') {
            //the new parent node
            $new_parent = $this->loadModel($new_parent_id);
            //the previous sibling node (after the move).
            if ($previous_node_id != 'false')
                $previous_node = $this->loadModel($previous_node_id);

            //if we move
            if ($copy == 'false') {
                if ($moved_node->moveAsFirst($new_parent)) {
                    echo json_encode(array('success' => true));
                    exit;
                }

                //if the moved node is only child of new parent node
                if ($previous_node_id == 'false' && $next_node_id == 'false') {

                    if ($moved_node->moveAsFirst($new_parent)) {
                        echo json_encode(array('success' => true));
                        exit;
                    }
                }

                    //if we moved it in the first position
                else if ($previous_node_id == 'false' && $next_node_id != 'false') {

                    if ($moved_node->moveAsFirst($new_parent)) {
                        echo json_encode(array('success' => true));
                        exit;
                    }
                }
                    //if we moved it in the last position
                else if ($previous_node_id != 'false' && $next_node_id == 'false') {

                    if ($moved_node->moveAsLast($new_parent)) {
                        echo json_encode(array('success' => true));
                        exit;
                    }
                }
                    //if the moved node is somewhere in the middle
                else if ($previous_node_id != 'false' && $next_node_id != 'false') {

                    if ($moved_node->moveAfter($previous_node)) {
                        echo json_encode(array('success' => true));
                        exit;
                    }

                }

            } //end of it's a move
                //else if it is a copy
            else {
                //create the copied Group model
                $copied_node = new Group;
                //copy the attributes (only safe attributes will be copied).
                $copied_node->attributes = $moved_node->attributes;
                //remove the primary key
                $copied_node->id = null;


                if ($copied_node->appendTo($new_parent)) {
                    echo json_encode(array('success' => true,
                                          'id' => $copied_node->primaryKey
                                     )
                    );
                    exit;
                }
            }


        } //if the new parent is not root end
            //else,move it as a new Root
        else {
            //if moved/copied node is not Root
            if (!$moved_node->isRoot()) {

                if ($moved_node->moveAsRoot()) {
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('success' => false));
                }

            }
                //else if moved/copied node is Root
            else {

                echo json_encode(array('success' => false, 'message' => 'Node is already a Root.Roots are ordered by id.'));
            }
        }

    }  //action moveCopy


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


}