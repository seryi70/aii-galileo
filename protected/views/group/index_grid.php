<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  index_grid Frontend view with GridView
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">
        <h3>Groups Admin with GridView</h3>
        <a class='headerlinks'  href="<?php echo $this->createUrl('admin_grid');?>">Admin GridView</a>
         <a  class='headerlinks'  href="<?php echo $this->createUrl('admin_list');?>">Admin ListView</a>
        <a   class='headerlinks' href="<?php echo $this->createUrl('index_list');?>">Frontend ListView</a>
         <br>
        <p>Right click on a group node  for details.Click on a group node  to filter Planet records.</p>
    </div>


    <div class="content-box-content  clearfix" id="wrapper">

        <div style="float:left">
            <input id="reload" type="button" style="display:block; clear: both;" value="Refresh Groups"
                   class="client-val-form button">
        </div>
        <div style="float:left">
            <input id="all" type="button" style="display:block; clear: both;" value="All Planets"
                   class="client-val-form button">
        </div>


        <!--The tree will be rendered in this div-->
        <div id="<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>" class="admintree">
        </div>

        <div id='category_info'>
            <div id='pic'></div>
            <div id='title'>
                                <h3><?php echo Group::model()->findByPK($category_id)->name;?></h3>
                            </div>
        </div>

       <?php    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('planet-grid', {
		data: $(this).serialize()
	});
	return false;
});
");?>
        <p>
            You may optionally enter a comparison operator (&lt;, &lt;=, &gt;, &gt;=, &lt;&gt; or =) at the beginning of
            each of your search values to specify how the comparison should be done.
        </p>

      <a class="search-button" href="#">Advanced Search</a>        <div class="search-form hide">
            <?php $this->renderPartial('application.views.planet._search', array(
                                                                                 'model' => $model,
                                                                            )); ?>        </div>
        <!-- search-form -->

        <?php
        //Strings for the delete confirmation dialog.
        $del_con = Yii::t('admin_planet', 'Are you sure you want to delete this planet?');
        $del_title = Yii::t('admin_planet', 'Delete Confirmation');
        $del = Yii::t('admin_planet', 'Delete');
        $cancel = Yii::t('admin_planet', 'Cancel')

        ;?>

          <div id="gridview-wrapper" class="left">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'planet-grid',
	'dataProvider' => $prod_dataProvider,
	'filter' => $model,
	'columns' => array(
		'id',
		'name',
		'planetGSM',
		'address',
		'NrSatellites',
		'installDate',
		/*
		'updateDate',
		'extra1',
		'extra2',
		'extra3',
		'extra4',
		*/
//      array(
//           'class' => 'CDataColumn',
//           'type' => 'image',
//           'value' => '($data->planetImgBehavior->getFileUrl("small"))?$data->planetImgBehavior->getFileUrl("small")."?".time() :Yii::app()->baseUrl."/img/placeholder_50.jpg". "?".time()',
//      ),
        array(
             'class' => 'CButtonColumn',
           'buttons' => array(
       'planet_view' => array(
                       'label' => Yii::t('admin_planet', 'View'), // text label of the button
                       'url' => '$data->id', // a PHP expression for generating the URL of the button
                       'imageUrl' => Yii::app()->request->baseUrl . '/js_plugins/ajaxform/images/icons/properties.png', // image URL of the button.   If not set or false, a text link is used
                       'options' => array("class" => "fan_view", 'title' => Yii::t('admin_product1', 'View')), // HTML options for the    button tag
                         )
                         ),
       'template' => '{planet_view}',
        ),
	),
  'afterAjaxUpdate' => 'js:function(id,data){$.bind_crud()}'
)); ?>

  </div>

    </div>
    <!--   Content-->
</div> <!-- ContentBox -->

<script type="text/javascript">
$(function () {

    $("#<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>")
            .jstree({
                "html_data" : {
                    "ajax" : {
                        "type":"POST",
                        "url" : "<?php echo $baseUrl;?>/group/fetchTree",
                        "data" : function (n) {
                            return {
                                id : n.attr ? n.attr("id") : 0,
                                "YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"
                            };
                        },
                        complete : function() {
                            $(".category_name").bind('click', function() {

                                var clicked = $(this);
                                var pk = clicked.attr('id');
                                $.fn.yiiGridView.update("planet-grid",
                                        {'data':{'cat_id':pk},
                                            'complete':function() {
                                                $('.category_selected').removeClass('category_selected');
                                                if (!clicked.hasClass('category_selected'))
                                                    clicked.addClass('category_selected');
                                                var timestamp2 = new Date().getTime();
                                                $('#title >h3').html(clicked.text());
                                                if (clicked.attr('rel') != 'no_image') $('#pic').fadeOut(400, function() {
                                                            $(this).html('<img class="cat_thumb" src=' + clicked.attr('rel') + '?' + timestamp2 + ' alt=' + clicked.text() + ' >').fadeIn(500);
                                                        }

                                                )
                                                else    $('#pic').fadeOut(500, function() {
                                                    $(this).html('')
                                                });

                                            }
                                        });
                                return false;
                            });
                        } //complete
                    }
                },

                "contextmenu":  { 'items': {

                    "create":false,
                    "rename":false,
                    "remove":false,
                    "ccp":false,
                    "properties" : {
                        "label"    : "Details",
                        "action" : function (obj) {
                            id = obj.attr("id").replace("node_", "")
                            $.ajax({
                                type:"POST",
                                url:"<?php echo $baseUrl;?>/group/returnView",
                                data:   {
                                    "id" :id,
                                    "YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"
                                },
                                beforeSend : function() {
                                    $("#<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>").addClass("ajax-sending");
                                },
                                complete : function() {
                                    $("#<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>").removeClass("ajax-sending");
                                },
                                success :  function(data) {
                                    $.fancybox(data,
                                            {    "transitionIn"    :    "elastic",
                                                "transitionOut"    :      "elastic",
                                                "scrolling":'no',
                                                "speedIn"        :    600,
                                                "speedOut"        :    200,
                                                "overlayShow"    :    false,
                                                "hideOnContentClick": false,
                                                "onClosed":    function() {
                                                } //onclosed function
                                            })//fancybox
                                } //function
                            });//ajax

                        },
                        "_class"            : "class",    // class is applied to the item LI node
                        "separator_before"    : false,    // Insert a separator before the item
                        "separator_after"    : true    // Insert a separator after the item

                    }//details

                }//items
                },//context menu

                // the `plugins` array allows you to configure the active plugins on this instance
                "plugins" : ["themes","html_data","contextmenu","crrm"],
                // each plugin you have included can have its own config object
                "core" : { "initially_open" : [  <?php echo $open_nodes;?> ],'open_parents':true}
                // it makes sense to configure a plugin only if overriding the defaults

            });


    //JSTREE FINALLY ENDS (Don't you just HATE javascript?!)

    //View  Planets  ---------------------------------------------------------------------

    //declaring the function that will bind behaviors to the gridview buttons.
    //also applied after an ajax update of the gridview.(see 'afterAjaxUpdate' attribute of gridview).
    //Only view details,since this is a frontend view.
    $.bind_crud = function() {

        $('.fan_view').each(function(index) {
            var id = $(this).attr('href');
            $(this).bind('click', function() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $baseUrl;?>/planet/returnView",
                    data:{"id":id,"YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"},
                    beforeSend : function() {
                        $("#planet-grid").addClass("ajax-sending");
                    },
                    complete : function() {
                        $("#planet-grid").removeClass("ajax-sending");
                    },
                    success: function(data) {
                        $.fancybox(data,
                                {    "transitionIn" : "elastic",
                                    "transitionOut" :"elastic",
                                    "scrolling":'no',
                                    "speedIn"              : 600,
                                    "speedOut"         : 200,
                                    "overlayShow"  : false,
                                    "hideOnContentClick": false
                                });//fancybox
                        //  console.log(data);
                    } //success
                });//ajax
                return false;
            });
        });
    }//bind_crud end

    //apply   $. bind_crud();
    $.bind_crud();


//REFRESH JSTREE
    $("#reload").click(function () {
        jQuery("#<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>").jstree("refresh");
    });


    //Show all  Planets
    $("#all").click(function () {
        $.fn.yiiGridView.update("planet-grid", {'data':{'cat_id':'all'}});
        $('#title > h3').html('All Planets');
        $('#pic').html('');
    });


});//doc ready

</script>

