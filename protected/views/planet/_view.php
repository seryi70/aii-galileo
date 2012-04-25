<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  _view.php   partial view for rendering  planet records  in ListView
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->

<?php 
/**If you want you can use $img_url in thumb source url,so that you can fall back to a placeholder image,if no image exists for model.*/
$thumb_src=$data->planetImgBehavior->getFileUrl('thumb');
$img_url=(isset($thumb_src))?$thumb_src :
Yii::app()->baseUrl.'/img/placeholder_100.jpg';

?>
<div class="list_partial_view">

    <?php echo GxHtml::encode($data->getAttributeLabel('id    ')); ?>:
  <?php echo GxHtml::encode($data->id)?>
    <br/>
    <div class='info'
    '>
    <div class='clear'>
        <?php echo GxHtml::encode($data->getAttributeLabel('name        ')); ?>:
                <?php echo GxHtml::encode($data->name); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('planetGSM        ')); ?>:
                <?php echo GxHtml::encode($data->planetGSM); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('address        ')); ?>:
                <?php echo GxHtml::encode($data->address); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('NrSatellites        ')); ?>:
                <?php echo GxHtml::encode($data->NrSatellites); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('installDate        ')); ?>:
                <?php echo GxHtml::encode($data->installDate); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('updateDate        ')); ?>:
                <?php echo GxHtml::encode($data->updateDate); ?>
                <br/>
        	<?php /*
        <?php echo GxHtml::encode($data->getAttributeLabel('extra1        ')); ?>:
                <?php echo GxHtml::encode($data->extra1); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('extra2        ')); ?>:
                <?php echo GxHtml::encode($data->extra2); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('extra3        ')); ?>:
                <?php echo GxHtml::encode($data->extra3); ?>
                <br/>
                <?php echo GxHtml::encode($data->getAttributeLabel('extra4        ')); ?>:
                <?php echo GxHtml::encode($data->extra4); ?>
                <br/>
        	*/ ?>
    </div>
    <div class="crud_buttons">
        <a id="view_<?php echo $data->id;?>"
           class="planet_properties"
           rel="<?php echo $data->name;?>" href="#">
            <img src="<?php  echo Yii::app()->baseUrl;?>/js_plugins/ajaxform/images/icons/properties.png"
                 alt="View"/>
        </a>
        <a id="update_<?php echo $data->id;?>"
           class="update_planet" href="#">
            <img src="<?php  echo Yii::app()->baseUrl;?>/js_plugins/ajaxform/images/icons/pencil.png"
                 alt="Update"/>
        </a>
        <a id="delete_<?php echo $data->id;?>"
           class="delete_planet"
           rel="<?php echo $data->name;?>" href="#">
            <img src="<?php  echo Yii::app()->baseUrl;?>/js_plugins/ajaxform/images/icons/cross.png"
                 alt="Delete"/>
        </a>
    </div>
</div>

</div>