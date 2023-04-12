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

use Joomla\CMS\HTML\HTMLHelper;

JHtml::addIncludePath(COM_TZ_PORTFOLIO_PLUS_ADMIN_HELPERS_PATH.DIRECTORY_SEPARATOR.'html');

$user		= JFactory::getUser();

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'd.ordering';
$addonId    = $this -> state -> get($this -> getName().'.addon_id');
$j4Compare  = COM_TZ_PORTFOLIO_PLUS_JVERSION_4_COMPARE;

if(!$j4Compare) {
    JHtml::_('behavior.multiselect');
    JHtml::_('bootstrap.tooltip');
    JHtml::_('dropdown.init');
    JHtml::_('formbehavior.chosen', 'select');
}else{
    $wa = $this->document->getWebAssetManager();
    $wa->useScript('table.columns')
        ->useScript('multiselect');
    HTMLHelper::_('formbehavior.chosen', 'select');
//    JHtml::_('formbehavior.chosen', 'select[multiple]');
}

if ($saveOrder)
{
    $saveOrderingUrl = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
        .'&addon_task=songs.saveOrderAjax&tmpl=component';
    if($j4Compare){
        HTMLHelper::_('draggablelist.draggable', 'addonDataList', '', strtolower($listDirn), $saveOrderingUrl);
    }else {
        JHtml::_('tzsortablelist.sortable', 'addonDataList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
    }
}
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)); ?>"
      method="post" name="adminForm" id="adminForm">

    <?php echo JHtml::_('tzbootstrap.addrow');?>
        <?php if(!empty($this -> sidebar)){?>
            <div id="j-sidebar-container" class="span2 col-md-2">
                <?php echo $this -> sidebar; ?>
            </div>
        <?php } ?>

        <?php echo JHtml::_('tzbootstrap.startcontainer', '10', !empty($this -> sidebar));?>

        <div class="tpContainer">
            <?php
            // Search tools bar
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>

            <?php if (empty($this->items)){ ?>
                <div class="alert alert-warning alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php }else{ ?>
                <table class="table table-striped itemList" id="addonDataList">
                    <thead>
                    <tr>
                        <th width="1%" class="nowrap center">
                            <?php echo JHtml::_('searchtools.sort', '', 'd.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'd.published', $listDirn, $listOrder); ?>
                        </th>
                        <th class="nowrap">
                            <?php echo JHtml::_('searchtools.sort','JGLOBAL_TITLE','value.title',$listDirn,$listOrder);?>
                        </th>
                        <th class="nowrap" width="20%">
                            <?php echo JText::_('PLG_CONTENT_MUSIC_ALBUM');?>
                        </th>
                        <th class="nowrap" width="1%">
                            <?php echo JHtml::_('searchtools.sort','JGRID_HEADING_ID','d.id',$listDirn,$listOrder);?>
                        </th>
                    </tr>
                    </thead>
                    <?php if($items = $this -> items):?>
                        <tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl;
                        ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php endif; ?>>
                        <?php foreach($items as $i => $data):
                            $canCreate  = $user->authorise('core.create',     'com_tz_portfolio_plus');
                            $canEdit    = $user->authorise('core.edit',       'com_tz_portfolio_plus');
                            $canEditOwn = $user->authorise('core.edit.own',   'com_tz_portfolio_plus');
                            $item   = $data -> value;
                            ?>
                            <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php
                            echo $data -> extension_id;?>" data-transitions="">
                                <td class="order nowrap center hidden-phone">
                                    <?php
                                    $canChange = $user->authorise('core.edit.state', 'com_tz_portfolio_plus.addons')
                                        || $user->authorise('core.edit.state.own', 'com_tz_portfolio_plus.addons');
                                    $iconClass = '';
                                    if (!$canChange)
                                    {
                                        $iconClass = ' inactive';
                                    }
                                    elseif (!$saveOrder)
                                    {
                                        $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::_('tooltipText', 'JORDERINGDISABLED');
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
                                    <?php echo JHtml::_('jgrid.published', $data->published, $i, 'songs.', true, 'cb'); ?>
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
                    <?php endif;?>

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
            <input type="hidden" name="addon_task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
        <?php echo JHtml::_('tzbootstrap.endcontainer');?>
    <?php echo JHtml::_('tzbootstrap.endrow');?>

</form>