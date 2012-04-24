<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<!--/**
 * MANY_MANY  Ajax Crud Administration
 *  _frontend_view.php   partial view for rendering  <?php echo $this->class2var($this->modelClass);?>
 *    records  in ListView,in frontend views.
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.0
 * @license The MIT License
 */-->

<?php echo '<?php '; ?>

/**If you want you can use $img_url in thumb source url,so that you can fall back to a placeholder image,if no image exists for model.*/

$thumb_src=$data-><?php echo $this->class2var($this->modelClass); ?>ImgBehavior->getFileUrl('thumb');
$img_url=(isset($thumb_src))?$thumb_src:
Yii::app()->baseUrl.'/img/placeholder_100.jpg';

<?php echo '?>'; ?>

<div class="list_partial_view">

    <?php echo '<?php'; ?> echo GxHtml::encode($data->getAttributeLabel('<?php echo $this->tableSchema->primaryKey; ?>
    ')); <?php echo '?>'; ?>:
    <?php echo '<?php'; ?> echo GxHtml::encode($data-><?php echo $this->tableSchema->primaryKey; ?>
    )<?php echo "?>\n"; ?>
    <br/>

    <div class='info'
    '>
    <div class='clear'>
        <?php
        $count = 0;
        foreach ($this->tableSchema->columns as $column):
            if ($column->isPrimaryKey)
                continue;
            if (++$count == 7)
                echo "\t<?php /*\n";
            ?>
            <?php echo '<?php'; ?> echo GxHtml::encode($data->getAttributeLabel('<?php echo $column->name; ?>
            ')); <?php echo '?>'; ?>:
            <?php if (!$column->isForeignKey): ?>
            <?php echo '<?php'; ?> echo GxHtml::encode($data-><?php echo $column->name; ?>); <?php echo "?>\n"; ?>
            <?php else: ?>
            <?php
            $relations = $this->findRelation($this->modelClass, $column);
            $relationName = $relations[0];
            ?>
            <?php echo '<?php'; ?> echo GxHtml::encode(GxHtml::valueEx($data-><?php echo $relationName; ?>
            )); <?php echo "?>\n"; ?>
            <?php endif; ?>
            <br/>
            <?php endforeach; ?>
        <?php
        if ($count >= 7)
            echo "\t*/ ?>\n";
        ?>
    </div>
    <div class="crud_buttons">
        <a id="view_<?php echo '<?php '; ?>echo $data->id;<?php echo '?>'; ?>"
           class="<?php echo $this->class2var($this->modelClass);?>_properties"
           rel="<?php echo '<?php '; ?>echo $data->name;<?php echo '?>'; ?>" href="#">
            <img
                src="<?php echo '<?php '; ?> echo Yii::app()->baseUrl;<?php echo '?>'; ?>/js_plugins/ajaxform/images/icons/properties.png"
                alt="View"/>
        </a>
    </div>
</div>
</div>