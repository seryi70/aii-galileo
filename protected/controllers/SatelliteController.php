<?php
/**
 * Ajax Crud Administration
 * SatelliteController *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */

class SatelliteController extends GxController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    public function   init() {
        $this->registerAssets();
        parent::init();
    }

    private function registerAssets(){
        Yii::app()->clientScript->registerCoreScript('jquery');
        //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
        //If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with
        //"onClosed"  // http://fancyapps.com/fancybox/#license
        // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
        // FancyBox
        //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
        //Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
        ///JSON2JS
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');

        //chosen,for multi selection in forms.
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/chosen/chosen.jquery.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/chosen/chosen.css');

        //jqueryform js
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');
    }

    /**
     * Returns options for the related model multiple select
     * @param string $model
     * @return  string options for relate model  multiple select
     * @since 1.0
     */

    public function related_opts($model){
        $relatedPKs= Planet::extractPkValue($model->parentPlanet);
        $options='';
        $products=GxHtml::listDataEx(Planet::model()->findAll(),$valueField ='id', $textField ='name');
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Satellite::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='satellite-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //AJAX CRUD
    public function actionReturnView(){
        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs=Yii::app()->clientScript;
        $cs->scriptMap=array(
            'jquery.min.js'=>false,
            'jquery.js'=>false,
            'jquery.fancybox-1.3.4.js'=>false,
            'jquery.fancybox.js'=>false,
            'jquery-ui-1.8.12.custom.min.js'=>false,
            'json2.js'=>false,
            'jquery.form.js'=>false,
            'form_ajax_binding.js'=>false
        );
        $model=$this->loadModel($_POST['id']);
        $this->renderPartial('view',array('model'=>$model),false, true);
    }

    public function actionReturnForm(){
        //Figure out if we are updating a Model or creating a new one.
        if(isset($_POST['update_id']))$model= $this->loadModel($_POST['update_id']);else $model=new Satellite;
        //  Comment out the following line if you want to perform ajax validation instead of client validation.
        //  You should also set  'enableAjaxValidation'=>true and
        //  comment  'enableClientValidation'=>true  in CActiveForm instantiation ( _ajax_form  file).
        //$this->performAjaxValidation($model);
        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs=Yii::app()->clientScript;
        $cs->scriptMap=array(
            'jquery.min.js'=>false,
            'jquery.js'=>false,
            'jquery.fancybox-1.3.4.js'=>false,
            'jquery.fancybox.js'=>false,
            'jquery-ui-1.8.12.custom.min.js'=>false,
            'json2.js'=>false,
            'jquery.form.js'=>false,
            'form_ajax_binding.js'=>false
        );
        $this->renderPartial('_ajax_form', array('model'=>$model), false, true);
    }

    public function actionIndex(){

        $model=new Satellite('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Satellite']))
            $model->attributes=$_GET['Satellite'];
        $this->render('index',array('model'=>$model));
    }

    public function actionAjax_Update(){
        if(isset($_POST['Satellite']))
        {
            $model=$this->loadModel($_POST['update_id']);
            $model->attributes=$_POST['Satellite'];
            if( $model->save(false)){
                echo json_encode(array('success'=>true));
            }else
                echo json_encode(array('success'=>false));
        }
    }

    public function actionAjax_Create(){
        if(isset($_POST['Satellite']))
        {
            $model=new Satellite;
            //set the submitted values
            $model->attributes=$_POST['Satellite'];
            //return the JSON result to provide feedback.
            if($model->save(false)){
                //echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
                //TODO: update sattelite counter on Planet
               //Planet::model()->updateCounters(array('NrSatellites'=>+1), 'id=' . $model->parentPlanetID );
                $sat = Planet::model()->findByPk($model->parentPlanetID);
                 if($sat->saveCounters(array('NrSatellites'=>1)))
                    echo json_encode(array('success'=>true,'id'=>$model->parentPlanetID) );;
/*                Planet::model()->updateCounters(
                    array('NrSatellites'=>+1),
                    array('condition' => "id = :id"),
                    array(':id' => $model->parentPlanetID),
                 );*/
                  exit;
            } else
            {
                echo json_encode(array('success'=>false));
                exit;
            }
        }
    }

    public function actionAjax_delete(){
        $id=$_POST['id'];
        $deleted=$this->loadModel($id);
        if ($deleted->delete() ){
            echo json_encode (array('success'=>true));
            exit;
        }else{
            echo json_encode (array('success'=>false));
            exit;
        }
    }
}