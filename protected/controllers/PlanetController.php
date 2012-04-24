<?php
/**
 * MANY_MANY  Ajax Crud Admnistration Demo
 * PlanetController * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */

class PlanetController extends GxController {


    public function actionCreate() {
        $model = new Planet;
        //	$this->performAjaxValidation($model, 'product-form');
        if (isset($_POST['Planet'])) {
            $model->setAttributes($_POST['Planet']);
            $relatedData = array(
                'groups' => $_POST['Planet']['groups'] === '' ? null : $_POST['Planet']['groups'],
            );
            if ($model->saveWithRelated($relatedData)) { //if model was saved
                echo '<textarea>' . json_encode(array('success' => true,
                                                      'id'      => $model->primaryKey)
                ) . '</textarea>';
                Yii::app()->end();
            } //else if model was not saved
            else {
                echo '<textarea>' . json_encode(array('success' => false)) . '</textarea>';
                Yii::app()->end();
            }
        }
    }

    public function actionUpdate() {
        // $this->performAjaxValidation($model, 'planet-form');
        if (isset($_POST['Planet'])) {
            $model = $this->loadModel($_POST['Planet']['update_id'], 'Planet');
            $model->setAttributes($_POST['Planet']);
            $relatedData = array(
                'groups' => $_POST['Planet']['groups'] === '' ? null : $_POST['Planet']['groups'],
            );
            if ($model->saveWithRelated($relatedData)) {
                echo '<textarea>' . json_encode(array('success'   => true,
                                                      'id'        => $model->primaryKey,
                                                      'thumb_url' => $model->planetImgBehavior->getFileUrl('thumb')
                    )
                ) . '</textarea>';
                Yii::app()->end();
            } else {
                echo '<textarea>' . json_encode(array('success' => false)) . '</textarea>';
                Yii::app()->end();
            }
        } //isset POST
    }

    public function actionDelete() {
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            if ($this->loadModel($_POST['id'], 'Planet')->delete()) {
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
    public function related_opts($model) {
        $relatedPKs = Group::extractPkValue($model->groups);
        $options = '';
        $categories = GxHtml::listDataEx(Group::model()->findAllAttributes(null, true));
        foreach ($categories as $value=> $text) {
            if (!$model->isNewRecord) {
                in_array($value, $relatedPKs) ?
                    $options .= '<option selected="selected" value=' . $value . '>' . $text . '</option>\n' :
                    $options .= '<option  value=' . $value . '>' . $text . '</option>\n';
            } else {
                $options .= '<option  value=' . $value . '>' . $text . '</option>\n';
            }
        }
        echo  $options;
    }

    public function actionReturnProductForm() {
        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs = Yii::app()->clientScript;
        $cs->scriptMap = array(
            'jquery.min.js'     => false,
            'jquery.js'         => false,
            'jquery.fancybox.js'=> false,
        );

        //if we are creating a new product
        if ($_POST['update'] != 'true') {
            $model = new Planet;
            //else load the model to update
        } else {
            $model = $this->loadModel($_POST['update_id'], Planet);
        }
        $this->renderPartial('_form', array('model'=> $model), false, true);
    }

    //renders the details view inside fancybox
    public function actionReturnView() {
        //don't reload these scripts or they will mess up the page
        //yiiactiveform.js still needs to be loaded that's why we don't use
        // Yii::app()->clientScript->scriptMap['*.js'] = false;
        $cs = Yii::app()->clientScript;
        $cs->scriptMap = array(
            'jquery.min.js'     => false,
            'jquery.js'         => false,
            'jquery.fancybox.js'=> false,
        );
        $model = $this->loadModel($_POST['id'], Planet);
        $this->renderPartial('view', array('model'=> $model), false, true);
    }
}