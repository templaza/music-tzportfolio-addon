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

use Joomla\CMS\Factory;

class TZ_Portfolio_Plus_Addon_MusicViewSong extends JViewLegacy{

    protected $item;
    protected $form;
    protected $state;
    protected $files;

    public function display($tpl=null){

        $this->state    = $this->get('State');
        $this->item     = $this->get('Item');
        $this->form     = $this->get('Form');
        if($item = $this -> item){
            if(!empty($item -> value) && isset($item -> value -> file_names)){
                $this -> files  = $item -> value -> file_names;
            }
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            Factory::getApplication() ->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }

        $this -> addToolbar();
        parent::display($tpl);

    }

    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);

        $user		= TZ_Portfolio_PlusUser::getUser();
        $userId		= $user->get('id');
        $isNew		= ($this->item->id == 0);
        $canDo	    = JHelperContent::getActions('com_tz_portfolio_plus');

        // For new records, check the create permission.
        if ($isNew && (count($user->getAuthorisedCategories('com_tz_portfolio_plus', 'core.create')) > 0)) {
            JToolBarHelper::apply('song.apply');
            JToolBarHelper::save('song.save');
            JToolBarHelper::save2new('song.save2new');
            JToolBarHelper::cancel('song.cancel');
        }else{
            // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
            if ($canDo->get('core.edit') || ($canDo->get('core.edit.own'))) {
                JToolBarHelper::apply('song.apply');
                JToolBarHelper::save('song.save');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ($canDo->get('core.create')) {
                    JToolBarHelper::save2new('song.save2new');
                }
            }

            // If checked out, we can still save
            if ($canDo->get('core.create')) {
                JToolBarHelper::save2copy('song.save2copy');
            }

            JToolBarHelper::cancel('song.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}