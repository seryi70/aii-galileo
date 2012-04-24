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
        <h2><?php echo $this->modelClass;?>s</h2><br>

        <h3>Frontend with GridView</h3>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('admin_grid');<?php echo '?>'; ?>">Admin
            GridView</a>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('admin_list');<?php echo '?>'; ?>">Admin
            ListView</a>
        <a class='headerlinks' href="<?php echo '<?php echo '; ?>$this->createUrl('index_list');<?php echo '?>'; ?>">Frontend
            ListView</a>
        <br>

        <p>Right click on a <?php echo $this->class2var($this->modelClass);?> node for details.Click on
            a <?php echo $this->class2var($this->modelClass);?> node to filter <?php echo  $relatedModelClass; ?>
            records.</p>
    </div>


    <div class="content-box-content  clearfix" id="wrapper">

        <div style="float:left">
            <input id="reload" type="button" style="display:block; clear: both;"
                   value="Refresh <?php echo $this->modelClass;?>s"
                   class="client-val-form button">
        </div>
        <div style="float:left">
            <input id="all" type="button" style="display:block; clear: both;"
                   value="All <?php echo $relatedModelClass;?>s"
                   class="client-val-form button">
        </div>


        <!--The tree will be rendered in this div-->
        <div
            id="<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>"
            class="admintree">
        </div>

        <div id='category_info'>
            <div id='pic'></div>
            <div id='title'>
                <?php    if ($category_id == 'all'): ?>
                <h3>All <?php echo $relatedModelClass; ?>s</h3>
                <?php else : ?>
                <h3><?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>
                    ::model()->findByPK($category_id)->name;<?php echo '?>'; ?></h3>
                <?php  endif;?>
            </div>
        </div>

        <?php echo '<?php  '; ?>  Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
        });
        $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('<?php echo $this->class2var($relatedModelClass);?>-grid', {
        data: $(this).serialize()
        });
        return false;
        });
        ")<?php echo ';?>'; ?>

        <p>
            You may optionally enter a comparison operator (&lt;, &lt;=, &gt;, &gt;=, &lt;&gt; or =) at the beginning of
            each of your search values to specify how the comparison should be done.
        </p>

        <?php echo GxHtml::link(Yii::t('app', 'Advanced Search'), '#', array('class' => 'search-button')); ?>
        <div class="search-form hide">
            <?php echo '<?php '; ?>
            $this->renderPartial('application.views.<?php echo  $this->class2var($relatedModelClass);?>._search', array(
            'model' => $model,
            )); <?php echo '?>'; ?>
        </div>
        <!-- search-form -->

        <?php echo '<?php  '; ?>

        //Strings for the delete confirmation dialog.
        $del_con = Yii::t('admin_<?php echo  $this->class2var($relatedModelClass); ?>', 'Are you sure you want to delete
        this <?php echo  $this->class2var($relatedModelClass); ?>?');
        $del_title = Yii::t('admin_<?php echo  $this->class2var($relatedModelClass); ?>', 'Delete Confirmation');
        $del = Yii::t('admin_<?php echo  $this->class2var($relatedModelClass); ?>', 'Delete');
        $cancel = Yii::t('admin_<?php echo  $this->class2var($relatedModelClass); ?>', 'Cancel')

        <?php echo ';?>' ?>


        <div id="gridview-wrapper" class="left">
            <?php echo '<?php'; ?> $this->widget('zii.widgets.grid.CGridView', array(
            'id' => '<?php echo  $this->class2var($relatedModelClass);?>-grid',
            'dataProvider' => $prod_dataProvider,
            'filter' => $model,
            'columns' => array(
            <?php
            $count = 0;
            foreach (GxActiveRecord::model($relatedModelClass)->tableSchema->columns as $column) {
                if (++$count == 7)
                    echo "\t\t/*\n";
                echo "\t\t" . $this->generateGridViewColumn($this->modelClass, $column) . ",\n";
            }
            if ($count >= 7)
                echo "\t\t*/\n";
            ?>
            array(
            'class' => 'CDataColumn',
            'type' => 'image',
            'value' => '($data-><?php echo  $this->class2var($relatedModelClass);?>
            ImgBehavior->getFileUrl("small"))?$data-><?php echo  $this->class2var($relatedModelClass);?>
            ImgBehavior->getFileUrl("small")."?".time() :Yii::app()->baseUrl."/img/placeholder_50.jpg". "?".time()',
            ),
            array(
            'class' => 'CButtonColumn',
            'buttons' => array(
            '<?php echo  $this->class2var($relatedModelClass);?>_view' => array(
            'label' => Yii::t('admin_<?php echo  $this->class2var($relatedModelClass);?>', 'View'), // text label of the
            button
            'url' => '$data->id', // a PHP expression for generating the URL of the button
            'imageUrl' => Yii::app()->request->baseUrl . '/js_plugins/ajaxform/images/icons/properties.png', // image
            URL of the button. If not set or false, a text link is used
            'options' => array("class" => "fan_view", 'title' => Yii::t('admin_product1', 'View')), // HTML options for
            the button tag
            )
            ),
            'template' => '{<?php echo  $this->class2var($relatedModelClass);?>_view}',
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
                                $.fn.yiiGridView.update("<?php echo  $this->class2var($relatedModelClass);?>-grid",
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

                    "create":false,
                    "rename":false,
                    "remove":false,
                    "ccp":false,
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
                                            "onClosed":function () {
                                            } //onclosed function
                                        })//fancybox
                                } //function
                            });//ajax

                        },
                        "_class":"class", // class is applied to the item LI node
                        "separator_before":false, // Insert a separator before the item
                        "separator_after":true    // Insert a separator after the item

                    }//details

                }//items
                }, //context menu

                // the `plugins` array allows you to configure the active plugins on this instance
                "plugins":["themes", "html_data", "contextmenu", "crrm"],
                // each plugin you have included can have its own config object
                "core":{ "initially_open":[  <?php echo '<?php '; ?>echo $open_nodes;<?php echo '?>'; ?> ],
        'open_parents'
        :
        true
    }
    // it makes sense to configure a plugin only if overriding the defaults

    })
    ;


    //JSTREE FINALLY ENDS (Don't you just HATE javascript?!)

    //View  <?php echo $relatedModelClass;?>s  ---------------------------------------------------------------------

    //declaring the function that will bind behaviors to the gridview buttons.
    //also applied after an ajax update of the gridview.(see 'afterAjaxUpdate' attribute of gridview).
    //Only view details,since this is a frontend view.
    $.bind_crud = function () {

        $('.fan_view').each(function (index) {
            var id = $(this).attr('href');
            $(this).bind('click', function () {
                $.ajax({
                    type:"POST",
                    url:"<?php echo '<?php echo '; ?>$baseUrl<?php echo ';?>'; ?>/<?php echo $this->class2var($relatedModelClass);?>/returnView",
                    data:{"id":id, "YII_CSRF_TOKEN":"<?php echo '<?php echo '; ?>Yii::app()->request->csrfToken;<?php echo '?>'; ?>"},
                    beforeSend:function () {
                        $("#<?php echo  $this->class2var($relatedModelClass);?>-grid").addClass("ajax-sending");
                    },
                    complete:function () {
                        $("#<?php echo  $this->class2var($relatedModelClass);?>-grid").removeClass("ajax-sending");
                    },
                    success:function (data) {
                        $.fancybox(data,
                            {    "transitionIn":"elastic",
                                "transitionOut":"elastic",
                                "scrolling":'no',
                                "speedIn":600,
                                "speedOut":200,
                                "overlayShow":false,
                                "hideOnContentClick":false
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
        jQuery("#<?php echo '<?php echo '; ?><?php echo $this->modelClass; ?>::ADMIN_TREE_CONTAINER_ID<?php echo ';?>'; ?>").jstree("refresh");
    });


    //Show all  <?php echo $relatedModelClass;?>s
    $("#all").click(function () {
        $.fn.yiiGridView.update("<?php echo  $this->class2var($relatedModelClass);?>-grid", {'data':{'cat_id':'all'}});
        $('#title > h3').html('All <?php echo $relatedModelClass; ?>s');
        $('#pic').html('');
    });


    })
    ;//doc ready

</script>

