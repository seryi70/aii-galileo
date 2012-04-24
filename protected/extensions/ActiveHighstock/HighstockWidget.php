<?php

/**
 * HighstockWidget  class file - seryi70's adaptation of
 * HighchartsWidget class file by
 *
 * @author Gavyn <gf010010@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.1
 *
 * Use as follows:
$this->Widget('ext.ActiveHighstock.HighstockWidget', array(
'dataProvider'=>$dataProvider,
'options'=> array(
'theme' => 'grid', //dark-blue dark-green gray grid skies
'rangeSelector'=>array('selected'=>1),
'credits' => array('enabled' => true),
'title'=>array('text'=>'Table title'),
'xAxis'=>array('maxZoom'=>'4 * 3600000' ),  //4 hours
'yAxis'=>array('title'=>array('text'=>'DataValueTitle')),
'series'=>array(
array(
'name'=>'SeriesTitle', //title of data
'dataResource'=>'data',     //data resource according to MySQL column 'data'
'dateResource'=>'time',     //data resource according to MySQL column 'time'
)
)
)
));
 */

Yii::import('zii.widgets.CBaseListView');

class HighstockWidget extends CBaseListView {

    public $action = array();
    public $options = array();
    public $htmlOptions = array();

    /**
     * Renders the widget.
     */
    public function run() {
        $id = $this->getId();
        $this->htmlOptions['id'] = $id;

        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        parent::run();

        // check if options parameter is a json string
        if (is_string($this->options)) {
            if (!$this->options = CJSON::decode($this->options))
                throw new CException('The options parameter is not valid JSON.');
        }

        // merge options with default values
        $defaultOptions = array('chart'     => array('renderTo' => $id),
                                'exporting' => array('enabled' => true));
        $this->options = CMap::mergeArray($defaultOptions, $this->options);
        $jsOptions = CJavaScript::encode($this->options);
        $this->registerScripts(__CLASS__ . '#' . $id, "$.chart = new Highcharts.StockChart($jsOptions);");

        $this->registerChartProcessScript($id);

        //self defined extra javascript
        $i = 0;
        $cs = Yii::app()->clientScript;
        foreach ($this->action as $ext) {
            $cs->registerScript("ext" . $i++, $ext, CClientScript::POS_BEGIN);
        }
    }

    public function renderItems() {
        $seriesNames = $this->dataProvider->model->attributeNames();
        $data = $this->dataProvider->getData();
        $series = $this->options['series'];

        // [date,data] pairs
        foreach ($series as $i=> $v) {
            if (isset($v['dataResource']) && isset($v['dateResource']) &&
                !is_array($v['dataResource']) && !is_array($v['dateResource'])
            ) {
                $seriesA = array();
                foreach ($data as $j=> $val) {
                    //sql UNIX_TIMESTAMP(s)-->JS UNIX timestamp(ms)
                    $seriesN = array(1000 * floatval($val[$v['dateResource']]),
                        floatval($val[$v['dataResource']]));
                    $seriesA[] = $seriesN;
                }
                //sorb by first column (dates ascending order)
                foreach ($seriesA as $key => $row) {
                    $dates[$key] = $row[0];
                }
                array_multisort($dates, SORT_ASC, $seriesA);
                $this->options['series'][$i]['data'] = $seriesA;
            }
        }

    }

    /**
     * Publishes and registers the necessary script files.
     *
     * @param string the id of the script to be inserted into the page
     * @param string the embedded script to be inserted into the page
     */
    protected function registerScripts($id, $embeddedScript) {
        $basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        $scriptFile = YII_DEBUG ? '/highstock.src.js' : '/highstock.js';

        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . $scriptFile);

        // register exporting module if enabled via the 'exporting' option
        if ($this->options['exporting']['enabled']) {
            $scriptFile = YII_DEBUG ? 'exporting.src.js' : 'exporting.js';
            $cs->registerScriptFile("$baseUrl/modules/$scriptFile");
        }

        // register global theme if specified vie the 'theme' option
        if (isset($this->options['theme'])) {
            $scriptFile = $this->options['theme'] . ".js";
            $cs->registerScriptFile("$baseUrl/themes/$scriptFile");
        }
        $cs->registerScript($id, $embeddedScript, CClientScript::POS_LOAD);
    }

    protected function registerChartProcessScript($id) {
        $basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        $cs = Yii::app()->clientScript;

        //register highcharts script
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').highstockview();");

        //to remove the non-use element style options from the selected class
        //$cs->registerScript(__CLASS__.'#'.$id+1, "$('.highcharts-container').each(function(idx,el){el.style.position='';});", CClientScript::POS_LOAD);

        $cs->registerScriptFile($baseUrl . '/jquery.yiihighstockview.js', CClientScript::POS_END);
    }
}