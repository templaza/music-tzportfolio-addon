<?php
/*------------------------------------------------------------------------

# Music Addon

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2016 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

extract($displayData);

$uri = new JUri($link);

$addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');

$modalIdNew = $modalId.'-new';

//if(COM_TZ_PORTFOLIO_PLUS_JVERSION_4_COMPARE) {
//    /** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
//    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
//
//    // Add the modal field script to the document head.
//    $wa->useScript('field.modal-fields');
//}else {
//    JHtml::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));
//}
$doc    = JFactory::getDocument();
$doc -> addScript(TZ_Portfolio_PlusUri::root(true).'/addons/content/music/admin/assets/js/modal-fields.js');
?>
<div class="btn-group btn-group-md d-flex">
    <a class="btn btn-primary hasTooltip modal_<?php echo $id; ?>" href="#<?php
    echo $modalId?>" title="<?php echo JText::_('PLG_CONTENT_MUSIC_CHANGE_SONGS');
    ?>"<?php echo $filter_field?' data-filter-field="'.$filter_field.'"':''; ?><?php
    echo $filter_field_id?' data-filter-field-id="'.$filter_field_id.'"':'';
    ?><?php echo $iframe_append?' rel="'.$uri.'"':'';
    ?> data-form-control="<?php echo $field -> formControl;
    ?>" data-toggle="modal" data-bs-toggle="modal"><span class="fa fa-copy mr-1"></span> <?php
        echo JText::_('JSELECT'); ?></a>
    <?php if ($allowNew) { ?>
    <a class="btn btn-outline-secondary" href="#<?php echo $modalIdNew;
    ?>" target="_blank" data-toggle="modal" data-bs-toggle="modal"><span class="fa fa-plus mr-1"></span> <?php
        echo JText::_('JACTION_CREATE'); ?></a>
<!--    <a href="--><?php //echo JRoute::_('index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id='
//        .$addon -> id.'&addon_task=song.add');
//    ?><!--" target="_blank" class="btn btn-outline-secondary"><span class="fa fa-plus mr-1"></span> --><?php
//        echo JText::_('JACTION_CREATE'); ?><!--</a>-->
    <?php } ?>
    <a href="javascript:" onclick="return addonMusicClearAll<?php echo $field -> type; ?>('<?php echo $id;
    ?>');" id="<?php echo $id; ?>_clear" class="btn btn-danger<?php echo $value ? '' : ' disabled';
    ?>"><span class="fa fa-times mr-1"></span> <?php echo JText::_('PLG_CONTENT_MUSIC_RESET'); ?></a>
</div>
<table class="table table-bordered table-striped table-responsive-md mt-3 mb-3 mh-100" id="<?php echo $id.'_table';?>">
    <thead>
    <tr>
        <th data-field-name="title"><?php echo JText::_('JGLOBAL_TITLE'); ?></th>
        <th data-field-name="id" width="8%"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
        <th width="10%"><?php echo JText::_('JSTATUS'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $totals  = array();

    if(isset($items) && $items){
        foreach($items as $item) {
            $song   = json_decode($item -> value);
            ?>
            <tr>
                <td><?php echo $song -> title; ?>
                    <input type="hidden" name="<?php echo $name; ?>"
                           value="<?php echo $item->id; ?>"/></td>
                <td><?php echo $item -> id; ?></td>

                <td class="text-center actions">
                    <div class="btn-group">
                        <?php if ($allowEdit) { ?>
                            <a class="btn btn-secondary btn-small btn-sm hasTooltip" target="_blank" title="<?php echo JText::_('JACTION_EDIT'); ?>"
                               href="index.php?option=com_tz_portfolio_plus&task=article.edit&id=<?php
                               echo $item->id; ?>"><span class="icon-edit"></span></a>
                        <?php } ?>
                        <a href="javascript:" class="btn btn-danger btn-small btn-sm hasTooltip" title="<?php echo JText::_('JTOOLBAR_REMOVE'); ?>"
                           onclick="addonMusicClear<?php echo $field -> type; ?>(this);"><span class="icon-remove"></span></a>
                    </div>
                </td>
            </tr>
            <?php
        }
    }?>
    </tbody>
</table>
<?php if($required){ ?>
<input type="hidden" value="<?php echo (isset($items) && $items)?1:''; ?>" <?php echo $required?' required':''; ?>/>
<?php } ?>
<?php
// Render the modal
$modalOption    = array(
    'title'      => JText::_('PLG_CONTENT_MUSIC_SELECT_SONGS'),
    'width'      => '100%',
    'height'     => '500px',
    'modalWidth' => '70',
    'bodyHeight' => '70',
    'closeButton' => true,
//    'class'       => 'w-75 w-sm-100 modal-block-full',
    'footer'      => '<a class="btn btn-default" data-dismiss="modal" data-bs-dismiss="modal">' . JText::_('JCANCEL') . '</a>',
);
if(!$iframe_append){
    $modalOption['url'] = $link;
}
echo JHtml::_(
    'bootstrap.renderModal',
    $modalId,
    $modalOption
);

// Create song modal
if($allowNew) {
    $modalOption['url'] = $link_new;
    $modalOption['footer'] = '<button type="button" class="btn btn-secondary"'
    . ' onclick="window.processModalEditAddonMusic(this, \'' . $field -> id . '\', \'add\', \'song\', \'cancel\', \'adminForm\'); return false;">'
    . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>'
    . '<button type="button" class="btn btn-primary"'
    . ' onclick="window.processModalEditAddonMusic(this, \'' . $field -> id . '\', \'add\', \'song\', \'save\', \'adminForm\',\'jform_id\',\'jform_value_title\'); return false;">'
    . JText::_('JSAVE') . '</button>'
    . '<button type="button" class="btn btn-success"'
    . ' onclick="window.processModalEditAddonMusic(this, \'' . $field -> id . '\', \'add\', \'song\', \'apply\', \'adminForm\',\'jform_id\',\'jform_value_title\'); return false;">'
    . JText::_('JAPPLY') . '</button>';
    echo JHtml::_(
        'bootstrap.renderModal',
        $modalIdNew,
        $modalOption
    );
}

$doc   = JFactory::getDocument();

//$doc -> addScript(JUri::root(true).'/components/com_clever_crm/js/crm_modal.js');
$doc -> addScriptDeclaration('
    (function($, window){
        "use strict";
        window.addonMusicClearAll'.$field -> type.' = function(id) {
            if(!$("#" + id + "_clear").hasClass("disabled")){
                $("#" + id + "_table tbody").html("");
                $("#" + id + "_clear").addClass("disabled");
            }
            return false;
        };

        window.addonMusicClear'.$field -> type.' = function(obj){
            $(obj).tooltip("hide");
            $(obj).parents("tr").first().remove();
        };
        window.'.$function.' = function(data){
            if(data && Object.keys(data).length){
                var fieldId = "'.$id.'",
                    html = $("<div/>");
                    
                var __fields = $("#" + fieldId + "_table [data-field-name]");
                    
                $.each(data, function(index, item){
                
                    var tr    = $("<tr/>");
                    
                    if(__fields.length){
                        
                        $.each(__fields, function(){
                            var __f_name = $(this).attr("data-field-name"),
                                __f_val = (typeof item[__f_name] !== "undefined" && item[__f_name])?item[__f_name]:"",
                                __column_attr = $(this).attr("data-column-attribute");
                                
                                __column_attr = (typeof __column_attr !== "undefined")?" " + __column_attr:"";
                                 
                            if(__f_name == "title"){
                                tr.append("<td" + __column_attr +">" + __f_val
                                + "<input type=\"hidden\" name=\"'.$name.'\" value=\""+ item["id"] +"\"/>"
                                +"</td>");
                            }else{
                                tr.append("<td" + __column_attr +">" + __f_val + "</td>");
                            }
                        });
                    }
                        
                    tr.append("<td class=\"text-center actions\">"
                            '.($allowEdit?'
                            + "<div class=\"btn-group\">"
                            + "<a class=\"btn btn-secondary btn-small btn-sm hasTooltip\" target=\"_blank\" title=\"'
        .JText::_('JACTION_EDIT').'\""
                             +"  href=\"index.php?option=com_tz_portfolio_plus&view=addon_datas'
                            .'&addon_task=song.edit&id="+ item["id"] +"\"><span"
                             +" class=\"icon-edit\"></span></a>"
                            ':'').'
                            + "<a href=\"javascript:\" class=\"btn btn-danger btn-small btn-sm hasTooltip\" title=\"'.JText::_('JTOOLBAR_REMOVE').'\""
                            + "  onclick=\"addonMusicClear'.$field -> type.'(this);\"><i class=\"icon-remove\"></i></a>"
                           '.($allowEdit?'+ "</div>"':'').'
                            +"</td>");
                    if(!$("#" + fieldId + "_table tbody input[value=\""+ item["id"] + "\"]").length){
                        html.append(tr);
                    }
                    '.($required?'$("#" + fieldId + "_table").next().val("1");':'').'
                });
                $("#'.$modalId.'").modal("hide");
                '.($multiple == true?'$("#" + fieldId + "_table tbody").prepend(html.html())':'$("#" + fieldId + "_table tbody").html(html.html())').'
                .find(".hasTooltip").tooltip({"html": true,"container": "body"});
                $("#" + fieldId + "_clear").removeClass("disabled");
            }
        };
    })(jQuery, window);');
