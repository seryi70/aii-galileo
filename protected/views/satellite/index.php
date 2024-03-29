<?php
/**
 * Ajax Crud Administration
 * Satellite * index.php view file
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */
?><?php
 $this->breadcrumbs=array(
	 'Manage Satellites'
);
?>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('satellite-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Satellites </h1>

  <p class="left">You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.</p><?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="right">
    <input id="add_satellite" type="button" style="display:block; clear: both;"
           value="Create Satellite" class="client-val-form button">
</div>

<?php
//Strings for the delete confirmation dialog.
$del_con = Yii::t('admin_satellite', 'Are you sure you want to delete this satellite?');
$del_title=Yii::t('admin_satellite', 'Delete Confirmation');
 $del=Yii::t('admin_satellite', 'Delete');
 $cancel=Yii::t('admin_satellite', 'Cancel');
   ?>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
         'id' => 'satellite-grid',
         'dataProvider' => $model->search(),
         'filter' => $model,
         'htmlOptions'=>array('class'=>'grid-view clear'),
          'columns' => array(
		'id',
		'name',
		'active',
//		'parentPlanetID',
    array('class' => 'CButtonColumn',
    'header'=>'Planet',
    'buttons' => array(
          'planet_view' => array(
               //'label' => $data->parentPlanetID,
               'url' => '$data->parentPlanetID',
               'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png',
               'options' => array("class" => "parent_view", 'title' => Yii::t('admin_satellite', 'Planet')),
               // 'viewButtonUrl'=>'Yii::app()->createUrl("/user/view", array("id" => $data->parentPlanetID))',
               )
            ),
          'template' => '{planet_view}',
        ),
		'address',
//		'installDate',
		'deactivateDate',
		/*
		'updateDate',
		'error',
		'extra1',
		'extra2',
		'extra3',
		'extra4',
		*/

    array(
                   'class' => 'CButtonColumn',
                    'buttons' => array(
                                'satellite_delete' => array(
                                'label' => Yii::t('admin_satellite', 'Delete'), // text label of the button
                                 'url' => '$data->id', // a PHP expression for generating the URL of the button
                                 'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/cross.png', // image URL of the button.   If not set or false, a text link is used
                                 'options' => array("class" => "fan_del", 'title' => Yii::t('admin_satellite', 'Delete')), // HTML options for the button   tag
                                 ),
                                'satellite_update' => array(
                                'label' => Yii::t('admin_satellite', 'Update'), // text label of the button
                                'url' => '$data->id', // a PHP expression for generating the URL of the button
                                'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/pencil.png', // image URL of the button.   If not set or false, a text link is used
                                'options' => array("class" => "fan_update", 'title' => Yii::t('admin_satellite', 'Update')), // HTML options for the    button tag
                                 ),
                                'satellite_view' => array(
                                 'label' => Yii::t('admin_satellite', 'View'), // text label of the button
                                 'url' => '$data->id', // a PHP expression for generating the URL of the button
                                 'imageUrl' =>Yii::app()->request->baseUrl .'/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
                                 'options' => array("class" => "fan_view", 'title' => Yii::t('admin_satellite', 'View')), // HTML options for the    button tag
                                 )
                               ),
                   'template' => '{satellite_view}{satellite_update}{satellite_delete}',
            ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'

                                            ));


   ?>
<script type="text/javascript">
//document ready
$(function() {

    //declaring the function that will bind behaviors to the gridview buttons,
    //also applied after an ajax update of the gridview.(see 'afterAjaxUpdate' attribute of gridview).
        $. bind_crud= function(){
//Parent VIEW

            $('.parent_view').each(function(index) {
                var id = $(this).attr('href');
                $(this).bind('click', function() {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->request->baseUrl;?>/planet/returnView",
                        data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                        beforeSend : function() {
                            $("#satellite-grid").addClass("ajax-sending");
                        },
                        complete : function() {
                            $("#satellite-grid").removeClass("ajax-sending");
                        },
                        success: function(data) {
                            $.fancybox(data,
                                    {    "transitionIn" : "elastic",
                                        "transitionOut" : "elastic",
                                        "speedIn"       : 600,
                                        "speedOut"      : 200,
                                        "overlayShow"   : false,
                                        "hideOnContentClick": false
                                    });//fancybox
                            //  console.log(data);
                        } //success
                    });//ajax
                    return false;
                });
            });

 //VIEW

    $('.fan_view').each(function(index) {
        var id = $(this).attr('href');
        $(this).bind('click', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/satellite/returnView",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#satellite-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#satellite-grid").removeClass("ajax-sending");
                },
                success: function(data) {
                    $.fancybox(data,
                            {    "transitionIn" : "elastic",
                                "transitionOut" :"elastic",
                                "speedIn"       : 600,
                                "speedOut"      : 200,
                                "overlayShow"   : false,
                                "hideOnContentClick": false
                            });//fancybox
                    //  console.log(data);
                } //success
            });//ajax
            return false;
        });
    });

//UPDATE

    $('.fan_update').each(function(index) {
        var id = $(this).attr('href');
        $(this).bind('click', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/satellite/returnForm",
                data:{"update_id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#satellite-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#satellite-grid").removeClass("ajax-sending");
                },
                success: function(data) {
                    $.fancybox(data,
                            {    "transitionIn"    : "elastic",
                                 "transitionOut"   : "elastic",
                                 "speedIn"         : 600,
                                 "speedOut"        : 200,
                                 "overlayShow"     : false,
                                 "hideOnContentClick": false,
                                "afterClose":    function() {
                                   var page=$("li.selected  > a").text();
                                $.fn.yiiGridView.update('satellite-grid', {url:'',data:{"Satellite_page":page}});
                                }//onclosed
                            });//fancybox
                    //  console.log(data);
                } //success
            });//ajax
            return false;
        });
    });


// DELETE

    var deletes = new Array();
    var dialogs = new Array();
    $('.fan_del').each(function(index) {
        var id = $(this).attr('href');
        deletes[id] = function() {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->request->baseUrl;?>/satellite/ajax_delete",
                data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                    $("#satellite-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#satellite-grid").removeClass("ajax-sending");
                },
                success: function(data) {
                    var res = jQuery.parseJSON(data);
                     var page=$("li.selected  > a").text();
                    $.fn.yiiGridView.update('satellite-grid', {url:'',data:{"Satellite_page":page}});
                }//success
            });//ajax
        };//end of deletes

        dialogs[id] =
           $('<div style="text-align:center;"></div>')
           .html('<?php echo  $del_con; ?><br><br>' + '<h2 style="color:#999999">ID: ' + id + '</h2>')
           .dialog(
            {
                autoOpen: false,
                title: '<?php echo  $del_title; ?>',
                modal:true,
                resizable:false,
                buttons: [
                 {
                     text: "<?php echo  $del; ?>",
                     click: function() {
                                      deletes[id]();
                                      $(this).dialog("close");
                                      }
                 },
                 {
                    text: "<?php echo $cancel; ?>",
                    click: function() {
                                       $(this).dialog("close");
                                       }
                 }
                ]
            }
                );

        $(this).bind('click', function() {
                                         dialogs[id].dialog('open');
                                         // prevent the default action, e.g., following a link
                                         return false;
                                        });
    });//each end

        }//bind_crud end

   //apply   $. bind_crud();
  $. bind_crud();


//CREATE

    $('#add_satellite ').click(function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl;?>/satellite/returnForm",
            data:{"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                beforeSend : function() {
                    $("#satellite-grid").addClass("ajax-sending");
                },
                complete : function() {
                    $("#satellite-grid").removeClass("ajax-sending");
                },
            success: function(data) {
                $.fancybox(data,
                        {   "transitionIn"    : "elastic",
                            "transitionOut"   : "elastic",
                            "speedIn"         : 600,
                            "speedOut"        : 200,
                            "scrolling"       : 'no',
                            "overlayShow"     : false,
                            "hideOnContentClick": false,
                            "afterClose":    function() {
                                   var page=$("li.selected  > a").text();
                                $.fn.yiiGridView.update('satellite-grid', {url:'',data:{"Satellite_page":page}});
                            } //onclosed function
                        });//fancybox
            } //success
        });//ajax
        //return false;
    });//click


})//document ready

</script>
