<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  admin_list Administration View with ListView
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->
<?php
$related_this_Class = $this->getRelations($this->modelClass);
$relatedModelClass = $related_this_Class[0][3];
$relationName = $related_this_Class[0][0];

$related_Related_Class = $this->getRelations($relatedModelClass);
$relatedRelatedModelClass = $related_Related_Class[0][3];
$relatedRelationName = $related_Related_Class[0][0];
?>

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">
        <h2><?php echo $this->modelClass;?></h2><br>

        <h3>Administration with ListView</h3>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('admin_grid');<?php echo '?>'; ?>">Admin
            GridView</a>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('index_grid');<?php echo '?>'; ?>">Frontend
            GridView</a>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('index_list');<?php echo '?>'; ?>">Frontend
            ListView</a>
    </div>
    <br>

    <div class="content-box-content  clearfix" id="wrapper">
        <ul>
            <li>If tree is empty,start by creating one or more root nodes.</li>
            <li>Right Click on a node to see available operations.</li>
            <li>Move nodes with Drag And Drop.You can move a non-root node to root position and vice versa.</li>
            <li>Root nodes cannot be reordered.Their order is fixed by id.</li>
        </ul>
        <p>Click on a <?php echo $this->class2var($this->modelClass);?> node to
            filter <?php echo  $relatedModelClass; ?> records</p>

        <div style="margin-bottom: 70px;">
            <div style="float:left">
                <input id="reload" type="button" style="display:block; clear: both;"
                       value="Refresh <?php echo $this->modelClass;?>"
                       class="client-val-form button">
            </div>
            <div style="float:left">
                <input id="add_root" type="button" style="display:block; clear: both;"
                       value="Create Root <?php echo $this->modelClass;?>"
                       class="client-val-form button">
            </div>
            <div style="float:right">
                <input id="add_<?php echo $this->class2var($relatedModelClass);?>" type="button"
                       style="display:block; clear: both;" value="New <?php echo $relatedModelClass;?>"
                       class="client-val-form button">
            </div>
            <div style="float:left">
                <input id="all" type="button" style="display:block; clear: both;"
                       value="All <?php echo $relatedModelClass; ?>s"
                       class="client-val-form button">
            </div>
        </div>


        <!--The tree will be rendered in this div-->
        <div
            id="<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>"
            class='left'>

        </div>

        <div id='category_info'>
            <div id='pic'></div>
            <div id='title'>
                <?php    if ($category_id == 'all'): ?>
                <h3>All <?php echo $relatedModelClass; ?>s</h3>
                <?php else : ?>
                <h3><?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::model()->findByPK($category_id)->name;
                    ?></h3>
                <?php  endif;?>
            </div>
        </div>

        <div id="listview-wrapper" class="left">
            <?php echo '<?php '; ?>
            $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $prod_dataProvider,
            'itemView' => 'application.views.<?php echo $this->class2var($relatedModelClass);?>._view',
            'id' => '<?php echo $this->class2var($relatedModelClass);?>-listview',
            'afterAjaxUpdate' => 'js:function(id,data){$.bind_crud()}',
            'pagerCssClass' => 'pager_wrapper clearfix'
            ));
            <?php echo ';?>'; ?>
        </div>

    </div>
    <!--   Content-->
</div> <!-- ContentBox -->
<script type="text/javascript">
$(function () {

    $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>")
        .jstree({
            "html_data":{
                "ajax":{
                    "type":"POST",
                    "url":"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/fetchTree",
                    "data":function (n) {
                        return {
                            id:n.attr ? n.attr("id") : 0,
                            "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
                        };
                    },
                    complete:function () {
                        $(".category_name").bind('click', function () {

                            var clicked = $(this);
                            var pk = clicked.attr('id');
                            $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview",
                                {'data':{'cat_id':pk},
                                    'complete':function () {
                                        $('.category_selected').removeClass('category_selected');
                                        if (!clicked.hasClass('category_selected'))
                                            clicked.addClass('category_selected');
                                        var timestamp2 = new Date().getTime();
                                        $('#title >h3').html(clicked.text());
                                        if (clicked.attr('rel') != 'no_image') $('#pic').fadeOut(400, function () {
                                                $(this).html('<img class="cat_thumb" src=' + clicked.attr('rel') + '?' + timestamp2 + ' alt=' + clicked.text() + ' >').fadeIn(500);
                                            }

                                        )
                                        else    $('#pic').fadeOut(500, function () {
                                            $(this).html('')
                                        });

                                    }
                                });
                            return false;
                        });
                    } //complete
                }
            },

            "contextmenu":{ 'items':{

                "rename":{
                    "label":"Rename",
                    "action":function (obj) {
                        this.rename(obj);
                    }
                },
                "update":{
                    "label":"Update",
                    "action":function (obj) {
                        id = obj.attr("id").replace("node_", "");
                        anchor = obj.find('category_name');

                        $.ajax({
                            type:"POST",
                            url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/returnForm",
                            data:{
                                'update_id':id,
                                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
                            },
                            'beforeSend':function () {
                                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
                            },
                            'complete':function () {

                                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");

                            },
                            success:function (data) {
                                $.fancybox(data,
                                    {    "transitionIn":"elastic",
                                        "transitionOut":"elastic",
                                        "scrolling":'no',
                                        "speedIn":600,
                                        "speedOut":200,
                                        "overlayShow":false,
                                        "hideOnContentClick":false,
                                        "beforeClose":function () {

                                            jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("refresh");
                                            $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview");


                                        } //beforeClose function
                                    })//fancybox

                            } //success
                        });//ajax

                    }//action function

                }, //update

                "properties":{
                    "label":"Details",
                    "action":function (obj) {
                        id = obj.attr("id").replace("node_", "")
                        $.ajax({
                            type:"POST",
                            url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/returnView",
                            data:{
                                "id":id,
                                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
                            },
                            beforeSend:function () {
                                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
                            },
                            complete:function () {
                                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
                            },
                            success:function (data) {
                                $.fancybox(data,
                                    {    "transitionIn":"elastic",
                                        "transitionOut":"elastic",
                                        "scrolling":'no',
                                        "speedIn":600,
                                        "speedOut":200,
                                        "overlayShow":false,
                                        "hideOnContentClick":false,
                                        "beforeClose":function () {
                                        } //beforeClose function
                                    })//fancybox

                            } //function



                        });//ajax

                    },
                    "_class":"class", // class is applied to the item LI node
                    "separator_before":false, // Insert a separator before the item
                    "separator_after":true    // Insert a separator after the item

                }, //properties

                "remove":{
                    "label":"Delete",
                    "action":function (obj) {
                        $('<div title="Delete Confirmation">\n\
                     <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>\n\
                    <?php echo $this->modelClass;?> <span style="color:#FF73B4;font-weight:bold;">' + (obj).attr('rel') + '</span> and all it\'s subcategories will be deleted.Are you sure?</div>')
                            .dialog({
                                resizable:false,
                                height:170,
                                modal:true,
                                buttons:{
                                    "Delete":function () {
                                        jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("remove", obj);
                                        $(this).dialog("close");
                                    },
                                    Cancel:function () {
                                        $(this).dialog("close");
                                    }
                                }
                            });

                    }
                }, //remove
                "create":{
                    "label":"Create",
                    "action":function (obj) {
                        this.create(obj);
                    },
                    "separator_after":false
                }

            }//items
            }, //context menu

            // the `plugins` array allows you to configure the active plugins on this instance
            "plugins":["themes", "html_data", "contextmenu", "crrm", "dnd"],
            // each plugin you have included can have its own config object
            "core":{ "initially_open":[ <?php echo '<?php '; ?>echo $open_nodes;<?php echo '?>'; ?> ],
    'open_parents'
    :
    true
}
// it makes sense to configure a plugin only if overriding the defaults

})

///EVENTS
.bind("rename.jstree", function (e, data) {
    $.ajax({
        type:"POST",
        url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/rename",
        data:{
            "id":data.rslt.obj.attr("id").replace("node_", ""),
            "new_name":data.rslt.new_name,
            "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
        },
        beforeSend:function () {
            $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
        },
        complete:function () {
            $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
        },
        success:function (r) {
            response = $.parseJSON(r);
            if (!response.success) {
                $.jstree.rollback(data.rlbk);
            } else {
                data.rslt.obj.attr("rel", data.rslt.new_name);
                if ($('.category_selected').attr('id') == data.rslt.obj.attr("id").replace("node_", ""))
                    $('#title >h3').html(data.rslt.new_name);
            }
            ;
        }
    });
})

    .bind("remove.jstree", function (e, data) {
        $.ajax({
            type:"POST",
            url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/remove",
            data:{
                "id":data.rslt.obj.attr("id").replace("node_", ""),
                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
            },
            beforeSend:function () {
                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
            },
            complete:function () {
                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
            },
            success:function (r) {

                response = $.parseJSON(r);
                if (response.deleted_id == $('.category_selected').attr('id')) {
                    $('#title >h3').html('');
                    $("#pic").html('');
                    $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview");
                }

                if (!response.success) {
                    $.jstree.rollback(data.rlbk);
                }
                ;
            }
        });
    })

    .bind("create.jstree", function (e, data) {
        newname = data.rslt.name;
        parent_id = data.rslt.parent.attr("id").replace("node_", "");
        $.ajax({
            type:"POST",
            url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/returnForm",
            data:{   'newname':newname,
                'parent_id':parent_id,
                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
            },
            beforeSend:function () {
                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
            },
            complete:function () {
                $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
            },
            success:function (data) {

                $.fancybox(data,
                    {    "transitionIn":"elastic",
                        "transitionOut":"elastic",
                        "scrolling":'no',
                        "speedIn":600,
                        "speedOut":200,
                        "overlayShow":false,
                        "hideOnContentClick":false,
                        "beforeClose":function () {
                            jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("refresh");
                        } //beforeClose function
                    })//fancybox

            } //success
        });//ajax

    })
    .bind("move_node.jstree", function (e, data) {
        data.rslt.o.each(function (i) {

            //jstree provides a whole  bunch of properties for the move_node event
            //not all are needed for this view,but they are there if you need them.
            //Commented out logs  are for debugging and exploration of jstree.

            next = jQuery.jstree._reference('#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>')._get_next(this, true);
            previous = jQuery.jstree._reference('#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>')._get_prev(this, true);

            pos = data.rslt.cp;
            moved_node = $(this).attr('id').replace("node_", "");
            next_node = next != false ? $(next).attr('id').replace("node_", "") : false;
            previous_node = previous != false ? $(previous).attr('id').replace("node_", "") : false;
            new_parent = $(data.rslt.np).attr('id').replace("node_", "");
            old_parent = $(data.rslt.op).attr('id').replace("node_", "");
            ref_node = $(data.rslt.r).attr('id').replace("node_", "");
            ot = data.rslt.ot;
            rt = data.rslt.rt;
            copy = typeof data.rslt.cy != 'undefined' ? data.rslt.cy : false;
            copied_node = (typeof $(data.rslt.oc).attr('id') != 'undefined') ? $(data.rslt.oc).attr('id').replace("node_", "") : 'UNDEFINED';
            new_parent_root = data.rslt.cr != -1 ? $(data.rslt.cr).attr('id').replace("node_", "") : 'root';
            replaced_node = (typeof $(data.rslt.or).attr('id') != 'undefined') ? $(data.rslt.or).attr('id').replace("node_", "") : 'UNDEFINED';


//                      console.log(data.rslt);
//                      console.log(pos,'POS');
//                      console.log(previous_node,'PREVIOUS NODE');
//                      console.log(moved_node,'MOVED_NODE');
//                      console.log(next_node,'NEXT_NODE');
//                      console.log(new_parent,'NEW PARENT');
//                      console.log(old_parent,'OLD PARENT');
//                      console.log(ref_node,'REFERENCE NODE');
//                      console.log(ot,'ORIGINAL TREE');
//                      console.log(rt,'REFERENCE TREE');
//                      console.log(copy,'IS IT A COPY');
//                      console.log( copied_node,'COPIED NODE');
//                      console.log( new_parent_root,'NEW PARENT INCLUDING ROOT');
//                      console.log(replaced_node,'REPLACED NODE');


            $.ajax({
                async:false,
                type:'POST',
                url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/moveCopy",

                data:{
                    "moved_node":moved_node,
                    "new_parent":new_parent,
                    "new_parent_root":new_parent_root,
                    "old_parent":old_parent,
                    "pos":pos,
                    "previous_node":previous_node,
                    "next_node":next_node,
                    "copy":copy,
                    "copied_node":copied_node,
                    "replaced_node":replaced_node,
                    "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
                },
                beforeSend:function () {
                    $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
                },
                complete:function () {
                    $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
                },
                success:function (r) {
                    response = $.parseJSON(r);
                    if (!response.success) {
                        $.jstree.rollback(data.rlbk);
                        alert(response.message);
                    }
                    else {
                        //if it's a copy
                        if (data.rslt.cy) {
                            $(data.rslt.oc).attr("id", "node_" + response.id);
                            if (data.rslt.cy && $(data.rslt.oc).children("UL").length) {
                                data.inst.refresh(data.inst._get_parent(data.rslt.oc));
                            }
                        }
                        //  console.log('OK');
                    }

                }
            }); //ajax

        });//each function
    });   //bind move event

;//JSTREE FINALLY ENDS (Don't you just HATE javascript??!)


//Crud for <?php echo $relatedModelClass;?>s----------------------------------------------------------------------

//Update

$.bind_crud = function () {

    //  function bind_product_crud(){
    $('a.update_<?php echo $this->class2var($relatedModelClass);?>').bind('click', function () {
        $.ajax({
            type:"POST",
            url:"<?php echo Yii::app()->baseUrl; ?>/<?php echo $this->class2var($relatedModelClass);?>/returnProductForm/",
            data:{'update_id':$(this).attr('id').replace("update_", ""),
                'update':true,
                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
            },
            beforeSend:function () {
                $("#listview-wrapper").addClass("ajax-sending");
            },
            complete:function () {
                $("#listview-wrapper").removeClass("ajax-sending");
            },
            success:function (data) {
                $.fancybox(data,
                    {    "transitionIn":"elastic",
                        "transitionOut":"elastic",
                        "scrolling":'no',
                        "speedIn":600,
                        "speedOut":200,
                        "overlayShow":false,
                        "hideOnContentClick":false,
                        "width":480,
                        "height":600,
                        "autoDimensions":true,
                        "beforeClose":function () {
                            var cat_pk = $('selected').attr('rel');
                            $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview", {'data':{'cat_id':cat_pk}
                                //           complete : function(){$.bind_product_crud();}
                            });
                        } //beforeClose function
                    }
                );//fan
                //  console.log(data);
            } //success
        });//ajax
        return false;
    });//bind


//View
    $('a.<?php echo $this->class2var($relatedModelClass);?>_properties').bind('click', function () {
        $.ajax({
            type:"POST",
            url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo $this->class2var($relatedModelClass);?>/returnView/",
            data:{'id':$(this).attr('id').replace("view_", ""),
                "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
            },
            beforeSend:function () {
                $("#listview-wrapper").addClass("ajax-sending");
            },
            complete:function () {
                $("#listview-wrapper").removeClass("ajax-sending");
            },
            success:function (data) {

                $.fancybox(data,
                    {    "transitionIn":"elastic",
                        "transitionOut":"elastic",
                        "scrolling":'no',
                        "speedIn":600,
                        "speedOut":200,
                        "overlayShow":false,
                        "hideOnContentClick":false,
                        "width":480,
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

//DELETE  PRODUCT BINDING
    $('a.delete_<?php echo $this->class2var($relatedModelClass);?>').bind('click', function () {
        product_id = $(this).attr('id').replace("delete_", "");
        product_title = $(this).attr('rel');
        var width = 250;
        var height = 270;
        var posX = $(this).offset().left - $(document).scrollLeft() - width + $(this).outerWidth();
        var posY = $(this).offset().top - $(document).scrollTop() + $(this).outerHeight();
        var dlg = $('<div title="Delete Confirmation">\n\
                     <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>\n\
                     <?php echo $relatedModelClass;?> <span style="color:#FF73B4;font-weight:bold;">' + product_title + '  (ID:  ' + product_id + '  )</span> will be deleted.Are you sure?</div>');
        var options = {
            resizable:false,
            height:170,

            position:[posX, posY],
            modal:true,
            zIndex:3000,
            buttons:{
                "Delete":function () {
                    $.ajax({
                        type:"POST",
                        url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo $this->class2var($relatedModelClass);?>/delete/",
                        data:{
                            'id':product_id,
                            "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
                        },
                        beforeSend:function () {
                            $("#listview-wrapper").addClass("ajax-sending");
                        },
                        complete:function () {
                            var cat_pk = $('selected').attr('rel');
                            $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview", {'data':{'cat_id':cat_pk}});
                            $("#listview-wrapper").removeClass("ajax-sending");
                        },
                        success:function (res) {
                            response = $.parseJSON(res);
                            if (response.success == true) {
                                $.fancybox.close();//alert('OK,Deleted.');
                            }
                        } //success
                    });//ajax
                    // return false;

                    $(this).dialog("close");
                },
                Cancel:function () {
                    $(this).dialog("close");
                }
            }
        };//options

        dlg.dialog(options);
        return false;

    });//bind


};//bind_product_update function

$.bind_crud();


//Binding events for <?php echo $this->modelClass;?> refresh, add root,add <?php echo $relatedModelClass;?>  buttons

$("#add_root").click(function () {
    $.ajax({
        type:'POST',
        url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo  $this->getControllerID();?>/returnForm",
        data:{
            "create_root":true,
            "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
        },
        beforeSend:function () {
            $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").addClass("ajax-sending");
        },
        complete:function () {
            $("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").removeClass("ajax-sending");
        },
        success:function (data) {

            $.fancybox(data,
                {    "transitionIn":"elastic",
                    "transitionOut":"elastic",
                    "scrolling":'no',
                    "speedIn":600,
                    "speedOut":200,
                    "overlayShow":false,
                    "hideOnContentClick":false,
                    "beforeClose":function () {
                        jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("refresh");

                    } //beforeClose function
                })//fancybox

        } //function

    });//post
});//click function


$("#add_<?php echo $this->class2var($relatedModelClass);?>").click(function () {
    $.ajax({
        type:'POST',
        url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo $this->class2var($relatedModelClass);?>/returnProductForm",
        data:{

            "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"
        },
        beforeSend:function () {
            $("#listview-wrapper").addClass("ajax-sending");
        },
        complete:function () {
            $("#listview-wrapper").removeClass("ajax-sending");

        },
        success:function (data) {

            $.fancybox(data,
                {    "transitionIn":"elastic",
                    "transitionOut":"elastic",
                    "scrolling":'no',
                    "speedIn":600,
                    "speedOut":200,
                    "overlayShow":false,
                    "hideOnContentClick":false,
                    "beforeClose":function () {
                        var cat_pk = $('selected').attr('rel');
                        $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview",
                            {'data':{'cat_id':cat_pk}
                                //      complete : function(){$.bind_product_crud()}
                            });
                    } //beforeClose function
                })//fancybox

        } //function

    });//post
});//click function


//REFRESH JSTREE
$("#reload").click(function () {
    jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("refresh");
});

$("#all").click(function () {
    $.fn.yiiListView.update("<?php echo $this->class2var($relatedModelClass);?>-listview", {'data':{'cat_id':'all'}});
    $('#title > h3').html('All <?php echo $relatedModelClass; ?>s');
    $('#pic').html('');
});


})
;//doc ready

</script>

