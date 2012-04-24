<?php
$this->breadcrumbs = array(
    'Measurements',
);

$this->menu = array(
//	array('label'=>'Create Measurement', 'url'=>array('create')),
//	array('label'=>'Manage Measurement', 'url'=>array('admin')),
);
?>
<h1>Measurements</h1>
<b><?php // echo CHtml::encode(Record::model()->getAttributeLabel('satelliteID')); ?>: </b>
<b><?php // echo CHtml::encode(Record::model()->getAttributeLabel('recordDate')); ?>: </b>
<b><?php // echo CHtml::encode(Record::model()->getAttributeLabel('recordData')); ?>: </b>

<?php
/*
  $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));
*/
?>
<div>
    <?php
/*
//CS Highcharts work perfectly fine, but I need Highstock
    $this->Widget('ext.ActiveHighcharts.HighchartsWidget', array(
        'dataProvider'=>$dataProvider,
        'template'=>'{items}',
        'options'=> array(
            'chart'=> array (
              'zoomType'=>'x',
//            'spacingRight'=>'20'
            ),
            'credits' => array('enabled' => false),
            'theme' => 'grid', //dark-blue dark-green gray grid skies
            'title'=>array('text'=>'Data View'),
          	'xAxis'=>array(
                // It cant be
                // 1.a self-defined xAxis array as you want;
                // 2.a series from datebase such as time series
//                'categories'=>'date',
                'type'=>'linear', //datetime logarithmic
                'maxZoom'=>'4*3600000' ,
                'title'=>array('text'=>null),
            ),
    		'yAxis'=>array('title'=>array('text'=>'Gas Consumption')),
            'series'=>array(
                array(
                    'type'=>'areaspline',
                    'name'=>'Satellite measurements', //title of data
                    'dataResource'=>'recordData',     //data resource according to database column
                    )
            )
        )
    ));

?>
</div><div>
<?php
*/
    $this->Widget('ext.ActiveHighstock.HighstockWidget', array(
        'dataProvider'=> $dataProvider,
        'options'     => array(
            'theme'        => 'grid', //dark-blue dark-green gray grid skies
            'rangeSelector'=> array('selected'=> 1),
            'credits'      => array('enabled' => false),
            'title'        => array('text'=> 'Data evolution'),
            'xAxis'        => array('maxZoom'=> '4 * 3600000'), //4 hours
            'yAxis'        => array('title'=> array('text'=> 'Gas Consumption')),
            'rangeSelector'=> array(
                'buttons' => array(
                    array(
                        'type' => 'day',
                        'count'=> '3',
                        'text' => '3d'),
                    array(
                        'type' => 'week',
                        'count'=> '1',
                        'text' => '1w'),
                    array(
                        'type' => 'month',
                        'count'=> '1',
                        'text' => '1m'),
                    array(
                        'type' => 'year',
                        'count'=> '1',
                        'text' => '1y'),
                    array(
                        'type'=> 'all',
                        'text'=> 'All')
                ),
                'selected'=> '3'),
            'series'       => array(
                array(
                    'name'        => 'All Satellites', //title of data
                    'dataResource'=> 'recordData', //data resource according to datebase column
                    'dateResource'=> 'recordDate', //data resource according to datebase column
                )
            )
        )
    ));


/*
//CS: make a refresh button
 * $pl=Satellite::model()->with('parentPlanet')->findAll();
$chart_id = $chart->getId();
    $refresh_button = $this->widget('zii.widgets.jui.CJuiButton', array(
        'buttonType'=>'button',
        'name'=>'refresh',
        'caption'=>'Refresh',
        'options'=>array(
        ),
        'onclick'=>'js:function(){
            url = window.location.href+"?";
            $.fn.highchartsview.update("'. $chart_id .'", url);
       }'
    ));
*/
    ?>
</div>