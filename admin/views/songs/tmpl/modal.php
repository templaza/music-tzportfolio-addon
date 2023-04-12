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

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(COM_TZ_PORTFOLIO_PLUS_ADMIN_HELPERS_PATH.DIRECTORY_SEPARATOR.'html');

$user		    = JFactory::getUser();
$input          = JFactory::getApplication() -> input;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'ordering';
$addonId    = $this -> state -> get($this -> getName().'.addon_id');

$function       = $input -> getCmd('function', 'addonMusicSongSelect');
$isMultiple     = $input -> get('ismultiple');

if ($saveOrder)
{
    $saveOrderingUrl = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
        .'&addon_task=songs.saveOrderAjax&tmpl=component';
    JHtml::_('tzsortablelist.sortable', 'addonMusicDataList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
    .($isMultiple?'&ismultiple=true':'')
    .'&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-inline">

    <div class="btn-toolbar">
        <a href="javascript:void(0)" onclick="addonMusicGetData();" class="btn btn-success">
            <span class="fa fa-check mr-1" aria-hidden="true"></span>
            <?php echo JText::_('COM_TZ_PORTFOLIO_PLUS_INSERT');?></a>
    </div>

    <?php
    // Search tools bar
    echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
    ?>

    <?php if (empty($this->items)){ ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
    <?php }else{ ?>
    <table class="table table-striped"  id="addonMusicDataList">
        <thead>
        <tr>
            <?php if($isMultiple){ ?>
            <th width="1%" class="hidden-phone">
                <?php echo JHtml::_('grid.checkall'); ?>
            </th>
            <?php } ?>
            <th width="1%" style="min-width:55px" class="nowrap center">
                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
            </th>
            <th class="nowrap">
                <?php echo JHtml::_('grid.sort','JGLOBAL_TITLE','value.title',$listDirn,$listOrder);?>
            </th>
            <th class="nowrap" width="20%">
                <?php echo JText::_('PLG_CONTENT_MUSIC_ALBUM');?>
            </th>
            <th class="nowrap" width="1%">
                <?php echo JHtml::_('grid.sort','JGRID_HEADING_ID','id',$listDirn,$listOrder);?>
            </th>
        </tr>
        </thead>

        <tbody>
        <?php
        $iconStates = array(
            -2 => 'icon-trash',
            0  => 'icon-times',
            1  => 'icon-check',
        );
        ?>
        <?php foreach($this->items as $i => $data):
            $canCreate  = $user->authorise('core.create',     'com_tz_portfolio_plus');
            $canEdit    = $user->authorise('core.edit',       'com_tz_portfolio_plus');
            $canEditOwn = $user->authorise('core.edit.own',   'com_tz_portfolio_plus');
            $item   = $data -> value;

            $select_data   = array('id' => $data -> id, 'title' => $item -> title);
            ?>
            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $data -> extension_id;?>">
                <?php if($isMultiple){ ?>
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $data->id, false, 'cid'); ?>
                </td>
                <?php } ?>
                <td class="text-center">
                        <span class="tbody-icon">
                            <span class="<?php echo $iconStates[$this->escape($data->published)]; ?>" aria-hidden="true"></span>
                        </span>
                </td>
                <td>
                    <a class="js-select-link" href="javascript:void(0)"><?php
                        echo $item -> title; ?></a>
                    <input type="hidden" name="addon_music[]" value='<?php
                    echo $this -> escape(json_encode($select_data));?>'>
                </td>
                <td><?php if(isset($data -> albums) && count($data -> albums) ){
                        foreach($data -> albums as $j => $album){
                            echo $album -> title;
                            if($j < count($data -> albums) - 1){
                                echo ', ';
                            }
                        }
                    }
                    ?></td>
                <td align="center hidden-phone"><?php echo $data -> id;?></td>
            </tr>
        <?php endforeach;?>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="11">
                <?php echo $this -> pagination -> getListFooter();?>
            </td>
        </tr>
        </tfoot>
    </table>
    <?php } ?>

    <input type="hidden" name="boxchecked" value="0">
<!--    <input type="hidden" name="filter_order" value="--><?php //echo $listOrder; ?><!--" />-->
<!--    <input type="hidden" name="filter_order_Dir" value="--><?php //echo $listDirn; ?><!--" />-->
    <input type="hidden" name="addon_task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php
$this -> document -> addScriptDeclaration('
(function($, window){
    $(document).ready(function(){
        $("#adminForm .js-select-link").on("click", function(event){
            event.preventDefault();
            var _btn = $(this),
                _val = _btn.next("input").val();
                
            if(typeof _val !== "undefined"){
                window.parent.'.$this->escape($function).'([JSON.parse(_val)]);
            }
        });
    });
})(jQuery, window);
function addonMusicGetData(){
    if (window.parent){
        var j= 0, data = new Array();
        if(document.getElementsByName("cid[]").length){
            var idElems  = document.getElementsByName("cid[]"),
                elVals  = document.getElementsByName("addon_music[]");
            for(var i = 0; i<idElems.length; i++){
                if(idElems[i].checked){
                    data[j] = JSON.parse(elVals[i].value);
                    j++;
                }
            }
            if(!data.length){
                alert("'.JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST').'");
                return false;
            }
            window.parent.'.$this->escape($function).'(data);
        }
    }
}
');
?>