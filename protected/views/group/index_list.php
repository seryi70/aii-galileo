<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  index_list Frontend view with ListView
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
        <a   class='headerlinks' href="<?php echo $this->createUrl('index_grid');?>">Frontend GridView</a>
           <br>
           <p>Right click on a group for details.Click on a group node  to filter Planet records.</p>
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

        <div id="<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>"  class="admintree" >
        </div>

       <div id='category_info'>
            <div id='pic'></div>
            <div id='title'>
                                <h3><?php echo Group::model()->findByPK($category_id)->name;?></h3>
                            </div>
        </div>


         <div id="listview-wrapper" class="left">
            <?php
 $this->widget('zii.widgets.CListView', array(
                                                'dataProvider' => $prod_dataProvider,
                                                'itemView' => 'application.views.planet._frontend_view',
                                                'id' => 'planet-listview',
                                                'afterAjaxUpdate' => 'js:function(id,data){$.bind_crud()}',
                                                'pagerCssClass' => 'pager_wrapper clearfix'
                                           ));

;?>        </div>


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
                                $.fn.yiiListView.update("planet-listview",
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
                                                "beforeClose":    function() {
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
                "core" : { "initially_open" : [ <?php echo $open_nodes;?> ],'open_parents':true}
                // it makes sense to configure a plugin only if overriding the defaults

            });     //JsTree


  //VIEW BINDING  ----------------------------------------------------------------------

    //declaring the function that will bind behaviors to the view buttons.
    //also applied after an ajax update of the listview.(see 'afterAjaxUpdate' attribute of gridview).
    //Only view details,since this is a frontend view.

    $.bind_crud = function() {

        $('a.planet_properties').bind('click', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo $baseUrl;?>/planet/returnView/",
                data:{'id': $(this).attr('id').replace("view_",""),
                    "YII_CSRF_TOKEN":"<?php echo Yii::app()->request->csrfToken;?>"
                },
                beforeSend : function() {
                    $("#listview-wrapper").addClass("ajax-sending");
                },
                complete : function() {
                    $("#listview-wrapper").removeClass("ajax-sending");
                },
                success: function(data) {

                    $.fancybox(data,
                            {    "transitionIn"    :    "elastic",
                                "transitionOut"    :      "elastic",
                                "scrolling":'no',
                                "speedIn"        :    600,
                                "speedOut"        :    200,
                                "overlayShow"    :    false,
                                "hideOnContentClick": false,
                                "width": 480,
                                "height":600,
                                "autoDimensions":true
                                // "margin":0,
                                //"padding":0
                            }
                    );
                    //  console.log(data);
                } //success
            });//ajax
            return false;
        });//bind

    };//bind_product_crud function

    $.bind_crud();

//REFRESH JSTREE
    $("#reload").click(function () {
        jQuery("#<?php echo Group::ADMIN_TREE_CONTAINER_ID;?>").jstree("refresh");
    });


    //Show all  Planets
    $("#all").click(function () {
        $.fn.yiiListView.update("planet-listview", {'data':{'cat_id':'all'}});
        $('#title > h3').html('All Planets');
        $('#pic').html('');
    });


});//doc ready

</script>

