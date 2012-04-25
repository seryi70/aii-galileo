<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.1
 *
 *  Usage:
 *
 *  1) Copy all the 'importcsv' folder under /protected/modules
 *
 *  2) Register module in /protected/config/main.php
 *     'modules'=>array(
 *		.........
 *               'importcsv'=>array(
 *			'path'=>'upload/importCsv/', // path to folder for saving csv file and file with import params
 *		),
 *              ......
 *	),
 *
 *  3) Do not forget to set permissions for directory 'path'
 *
 *  4) The module is available at http://yourproject/importcsv
 *
 */

class DefaultController extends SBaseController
{
        public $colsArray = array();

        /*
         * action for form
         */

	public function actionIndex()
	{
            $delimiter=";";
           //publish css and js

           Yii::app()->clientScript->registerCssFile(
                Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('application.modules.importcsv.assets').'/styles.css'
                )
            );

            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('application.modules.importcsv.assets').'/ajaxupload.js'
                )
            );

            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('application.modules.importcsv.assets').'/download.js'
                )
            );

           //getting all tables from db

           $tables = Yii::app()->getDb()->getSchema()->getTableNames();

           $tablesLength = sizeof($tables);
           $tablesArray = array();
           for($i=0; $i<$tablesLength; $i++) {
                $tablesArray[$tables[$i]] = $tables[$i];
           }

           if(Yii::app()->request->isAjaxRequest){
               if($_POST['thirdStep']!=1) {

                   //second step

                    $delimiter = CHtml::encode(trim($_POST['delimiter']));
                    $table = CHtml::encode($_POST['table']);

                    if($_POST['delimiter']=='') {
                        $error=1;
                    }
                    else {

                        // get all columns from csv file

                        $error=0;
                        $uploaddir    = Yii::app()->controller->module->path;
                        $uploadfile   = $uploaddir.basename($_POST['fileName']);
                        $filecontent  = explode("\n", file_get_contents($uploadfile));
                        $csvFirstLine = explode($delimiter, $filecontent[0]);

                        //get all columns from selected table

                         $model=new ImportCsv;
                         $tableColumns = $model->tableColumns($table);
                    }

                    // checking file with earlier imports

                    $paramsArray = $this->checkOldFile($uploadfile);

                    $this->layout='clear';
                    $this->render('secondResult', array(
                        'error'=>$error,
                        'tableColumns'=>$tableColumns,
                        'delimiter'=>$delimiter,
                        'table'=>$table,
                        'fromCsv'=>$csvFirstLine,
                        'paramsArray'=>$paramsArray,
                    ));
               }
               else {

                    //third step

                   if(array_sum($_POST['Poles'])>0) {
                      if($_POST['perRequest']!='') {
                          if(is_numeric($_POST['perRequest'])) {
                              $tableKey = CHtml::encode($_POST['Tablekey']);
                              $csvKey   = CHtml::encode($_POST['CSVkey']);
                              $mode     = CHtml::encode($_POST['Mode']);

                              if(($mode == 2 || $mode == 3) && ($tableKey == '' || $csvKey == '')) {
                                    $error = 4;
                              }
                              else {
                                   $error      = 0;
                                   $delimiter  = CHtml::encode(trim($_POST['thirdDelimiter']));
                                   $table      = CHtml::encode($_POST['thirdTable']);
                                   $uploadfile = CHtml::encode(trim($_POST['thirdFile']));
                                   $poles      = $_POST['Poles'];
                                   $perRequest = CHtml::encode($_POST['perRequest']);

                                   //import from csv to db

                                   $model = new ImportCsv;
                                   $tableColumns = $model->tableColumns($table);

                                   //select old rows from table

                                   if($mode == 2 || $mode == 3) {
                                       $oldItems = $model->selectRows($table, $tableKey);
                                   }

                                   $replace     = addslashes(file_get_contents($uploadfile));
                                   $filecontent = explode("\n", $replace);
                                   $lengthFile  = sizeof($filecontent);
                                   $insertCounter  = 0;
                                   $insertArray  = array();
                                   $error_array = array();
                                   $stepsOk = 0;

                                   // begin transaction

                                   $transaction = Yii::app()->db->beginTransaction();
                                   try {

                                       // import to database

                                       for($i=0; $i<$lengthFile; $i++) {
                                           if($i!=0 && $filecontent[$i]!='') {
                                               $csvLine = explode($delimiter, $filecontent[$i]);

                                               //Mode 1. insert All

                                               if($mode==1) {
                                                   $insertArray[] =  $csvLine;
                                                   $insertCounter++;
                                                   if($insertCounter == $perRequest || $i == $lengthFile-1) {
                                                        $import = $model->InsertAll($table, $insertArray, $poles, $tableColumns);
                                                        $insertCounter = 0;
                                                        $insertArray = array();

                                                        if($import != 1)
                                                            $arrays[] = $i;
                                                   }
                                               }

                                               // Mode 2. Insert new

                                               if($mode==2) {
                                                    if($csvLine[$csvKey-1]=='' || !$this->searchInOld($oldItems, $csvLine[$csvKey-1], $tableKey)){
                                                        $insertArray[] =  $csvLine;
                                                        $insertCounter++;
                                                        if($insertCounter == $perRequest || $i == $lengthFile-1) {
                                                            $import = $model->InsertAll($table, $insertArray, $poles, $tableColumns);
                                                            $insertCounter = 0;
                                                            $insertArray = array();

                                                            if($import != 1)
                                                                $arrays[] = $i;
                                                        }
                                                    }
                                               }

                                               // Mode 3. Insert new and replace old

                                               if($mode==3) {

                                                    if($csvLine[$csvKey-1]=='' || !$this->searchInOld($oldItems, $csvLine[$csvKey-1], $tableKey)){

                                                        // insert new

                                                        $linesArray[] =  $csvLine;
                                                        $strCounter++;
                                                        if($strCounter == $perRequest || $i == $lengthFile-1) {
                                                            $import = $model->InsertAll($table, $linesArray, $poles, $tableColumns);
                                                            $strCounter = 0;
                                                            $linesArray = array();

                                                            if($import != 1)
                                                                $arrays[] = $i;
                                                        }
                                                    }
                                                    else {

                                                        //replace old

                                                         $import = $model->updateOld($table, $csvLine, $poles, $tableColumns, $csvLine[$csvKey-1], $tableKey);

                                                         if($import != 1)
                                                             $arrays[] = $i;
                                                    }
                                               }
                                           }
                                       }

                                       if($insertCounter!=0) $model->InsertAll($table, $insertArray, $poles, $tableColumns);

                                       // commit transaction if not exception

                                       $transaction->commit();

                                   }
                                   catch(Exception $e) // exception in transaction
                                   {
                                       $transaction->rollBack();
                                   }

                                   // save params in file
                                   $this->saveInFile($table, $delimiter, $mode, $perRequest, $csvKey, $tableKey, $tableColumns, $poles, $uploadfile);
                              }
                           }
                           else {
                               $error = 3;
                           }
                       }
                       else {
                           $error = 2;
                       }
                   }
                   else {
                       $error = 1;
                   }

                   $this->layout='clear';
                   $this->render('thirdResult', array(
                        'error'=>$error,
                        'delimiter'=>$delimiter,
                        'table'=>$table,
                        'uploadfile'=>$uploadfile,
                        'error_array'=>$error_array,
                    ));
               }

               Yii::app()->end();
            }
            else {
                // first loading

                $this->render('index', array(
                    'delimiter'=>$delimiter,
                    'tablesArray'=>$tablesArray,
                ));
           }
	}

        /*
         * action for file upload
         */

        public function actionUpload()
        {
            $uploaddir  = Yii::app()->controller->module->path;
            $uploadfile = $uploaddir.basename($_FILES['myfile']['name']);

            $name_array = explode(".", $_FILES['myfile']['name']);
            $type = end($name_array);

            if($type=="csv") {
                if(move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)) {
                    $importError=0;
                }
                else {
                   $importError=1;
                }
            }
            else {
                $importError=2;
            }

            // checking file with earlier imports

            $paramsArray       = $this->checkOldFile($uploadfile);
            $delimiterFromFile = $paramsArray['delimiter'];
            $tableFromFile     = $paramsArray['table'];

            // view rendering

            $this->layout='clear';
            $this->render('firstResult', array(
                 'error'=>$importError,
                 'uploadfile'=>$uploadfile,
                 'delimiterFromFile'=>$delimiterFromFile,
                 'tableFromFile'=>$tableFromFile,
            ));
        }

        /*
         * search needle in old rows
         *
         *  $array  - array with old items from database
         *  $needle - value from csv
         *  $key    - db pole, where we search $needle
         *  @return boolean
         *
         */

        public function searchInOld($array, $needle, $key)
        {
            $return = false;
            $arrayLength = sizeof($array);
            for($i=0; $i<$arrayLength; $i++)
            {
                if($array[$i][$key]==$needle) $return = true;
            }

            return $return;
        }

        /*
         * save import params in php file, for using in next imports
         *
         *  $table - db table
         *  $delimiter - csv delimiter
         *  $mode - import mode
         *  $perRequest - items in one INSERT - mode
         *  $csvKey - key for compare from csv
         *  $tableKey - key for compare from table
         *  $tableColumns - list of table columns
         *  $poles - list of csv poles
         *  $uploadfile - path to import file
         *
         */

        public function saveInFile($table, $delimiter, $mode, $perRequest, $csvKey, $tableKey, $tableColumns, $poles, $uploadfile)
        {
            $columnsSize = sizeof($tableColumns);
            $columns     = '';
            for($i=0; $i<$columnsSize; $i++) {
                $columns = ($poles[$i]!="") ? $columns.'"'.$tableColumns[$i].'"=>'.$poles[$i].', ' : $columns.'"'.$tableColumns[$i].'"=>"", ' ;
            }

            $arrayToFile = '<?php
                $paramsArray = array(
                    "table"=>"'.$table.'",
                    "delimiter"=>"'.$delimiter.'",
                    "mode"=>'.$mode.',
                    "perRequest"=>'.$perRequest.',
                    "csvKey"=>"'.$csvKey.'",
                    "tableKey"=>"'.$tableKey.'",
                    "columns"=>array(
                        '.$columns.'
                    ),
                );
            ?>';

            $uploadfileArray = explode(".", $uploadfile);
            $uploadfileArray[sizeof($uploadfileArray)-1] = "php";
            $uploadfileNew = implode(".", $uploadfileArray);

            $fileForWrite = fopen($uploadfileNew,"w+");
            fwrite($fileForWrite, $arrayToFile);
            fclose($fileForWrite);
        }

        /*
         * checking file with earlier imports
         *
         * $uploadfile - path to import file
         * @return array Old params from file
         *
         */

        public function checkOldFile($uploadfile)
        {
            $selectfileArray = explode(".", $uploadfile);
            $selectfileArray[sizeof($selectfileArray)-1] = "php";
            $selectfileNew = implode(".", $selectfileArray);

            if(file_exists($selectfileNew)) {
                require_once($selectfileNew);
            }
            else {
                $paramsArray['delimiter']  = ";";
                $paramsArray['table']      = "";
                $paramsArray['mode']       = "";
                $paramsArray['perRequest'] = "10";
                $paramsArray['csvKey']     = "";
                $paramsArray['tableKey']   = "";
                $paramsArray['columns']    = array();
            }

            return $paramsArray;
        }
}