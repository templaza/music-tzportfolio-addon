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

$user		= JFactory::getUser();

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'ordering';
$addonId    = $this -> state -> get($this -> getName().'.addon_id');

if ($saveOrder)
{
    $saveOrderingUrl = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
        .'&addon_task=songs.saveOrderAjax&tmpl=component';
    JHtml::_('tzsortablelist.sortable', 'addonDataList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)); ?>"
      method="post" name="adminForm" id="adminForm" class="form-inline">
    <div id="filter-bar" class="btn-toolbar">
        <div class="btn-group pull-left">
            <label for="filter_search">
                <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
            </label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
        </div>
        <div class="btn-group pull-left">
            <button type="submit" class="btn hasTooltip" data-placement="bottom" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                <i class="icon-search"></i></button>
            <button type="button" class="btn hasTooltip" data-placement="bottom" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
                <i class="icon-remove"></i></button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn" onclick="tzGetDatas();">
                <i class="icon-save-new"></i> <?php echo JText::_('COM_TZ_PORTFOLIO_PLUS_INSERT');?></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <hr class="hr-condensed" />

    <table class="table table-striped"  id="addonDataList">
        <thead>
        <tr>
            <th width="1%" class="nowrap center">
                <?php echo JHtml::_('grid.sort', '<span class="icon-menu-2"></span>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
            </th>
            <th width="1%" class="hidden-phone">
                <?php echo JHtml::_('grid.checkall'); ?>
            </th>
            <th width="1%" style="min-width:55px" class="nowrap center">
                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
            </th>
            <th class="nowrap">
                <?php echo JHtml::_('grid.sort','JGLOBAL_TITLE','value.title',$listDirn,$listOrder);?>
            </th>
            <th class="nowrap" width="1%">
                <?php echo JHtml::_('grid.sort','JGRID_HEADING_ID','id',$listDirn,$listOrder);?>
            </th>
        </tr>
        </thead>
        <?php if($items = $this -> items):?>
            <tbody>
            <?php foreach($items as $i => $data):
                $canCreate  = $user->authorise('core.create',     'com_tz_portfolio_plus');
                $canEdit    = $user->authorise('core.edit',       'com_tz_portfolio_plus');
                $canEditOwn = $user->authorise('core.edit.own',   'com_tz_portfolio_plus');
                $item   = $data -> value;
                ?>
                <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $data -> extension_id;?>">
                    <td class="order nowrap center hidden-phone">
                        <?php
                        $canChange = $user->authorise('core.edit.state', 'com_tz_portfolio_plus.addons');
                        $iconClass = '';
                        if (!$canChange)
                        {
                            $iconClass = ' inactive';
                        }
                        elseif (!$saveOrder)
                        {
                            $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                        }
                        ?>
                        <span class="sortable-handler<?php echo $iconClass ?>">
                <span class="icon-menu"></span>
            </span>
                        <?php if ($canChange && $saveOrder) : ?>
                            <input type="text" style="display:none" name="order[]" size="5"
                                   value="<?php echo $data->ordering;?>" class="width-20 text-area-order " />
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $data->id, false, 'cid'); ?>
                    </td>
                    <td class="center">
                        <div class="btn-group">
                            <?php echo JHtml::_('jgrid.published', $data->published, $i, 'songs.', true, 'cb'); ?>
                        </div>
                    </td>
                    <td>
                        <?php if ($canEdit || $canEditOwn) : ?>
                            <a href="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
                                .'&addon_task=song.edit&id=' . (int) $data->id); ?>">
                                <?php echo $this->escape($item->title); ?></a>
                        <?php else : ?>
                            <?php echo $this->escape($item->title); ?>
                        <?php endif; ?>
                        <span class="small break-word">
                            <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                        </span>
                    </td>
                    <td align="center hidden-phone"><?php echo $data -> id;?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        <?php endif;?>

        <tfoot>
        <tr>
            <td colspan="11">
                <?php echo $this -> pagination -> getListFooter();?>
            </td>
        </tr>
        </tfoot>
    </table>

        <input type="hidden" name="boxchecked" value="0">
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="addon_task" value="" />
        <?php echo JHtml::_('form.token'); ?>
</form>
<script>
    //    $.JSortableList()
</script>