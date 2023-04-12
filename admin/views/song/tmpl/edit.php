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

HTMLHelper::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidator');

$input      = JFactory::getApplication() -> input;
$addonId    = $this -> state -> get($this -> getName().'.addon_id');
$group      = 'value';

// In case of modal
$isModal = $input->get('addon_layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

//JFactory::getDocument()->addScriptDeclaration('
//	Joomla.submitbutton = function(task)
//	{
//		if (task == "song.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
//		{
//			Joomla.submitform(task, document.getElementById("adminForm"));
//		}
//	};
//');
?>
<form action="<?php echo JRoute::_(TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addonId)
    .'&addon_view=song&addon_layout='.$layout.'&id='.(int) $this -> item -> id).$tmpl; ?>"
      method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data" class="tpArticle">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('JDETAILS', true)); ?>
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('title',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('title',$group);?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('alias',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('alias',$group);?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('published');?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('published');?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('contentid',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('contentid',$group);?></div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('thumbnail',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('thumbnail',$group);?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('file',$group);?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('file',$group);?>
                        <?php echo $this -> form -> getInput('file_names',$group);?>
                        <?php echo $this -> loadTemplate('files');?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this -> form -> getLabel('id');?></div>
                    <div class="controls"><?php echo $this -> form -> getInput('id');?></div>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this -> form -> getLabel('description',$group);?></div>
            <div class="controls"><?php echo $this -> form -> getInput('description',$group);?></div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="addon_task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
