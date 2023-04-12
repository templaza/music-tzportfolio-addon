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

JLoader::import('content.music.admin.helpers.toolbar', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH);

class TZ_Portfolio_Plus_Addon_MusicViewSongs extends JViewLegacy{

    protected $state;
    protected $items;
    protected $form;
    protected $sidebar;
    protected $pagination;

    public function display($tpl=null){

        $this->state            = $this->get('State');
        $this->items            = $this->get('Items');
        $this->pagination       = $this->get('pagination');
        $this -> filterForm     = $this -> get('FilterForm');
        $this -> activeFilters  = $this -> get('ActiveFilters');

        TZ_Portfolio_Plus_Addon_MusicHelper::addSubmenu('songs');

        if ($this->getLayout() !== 'modal')
        {
            $this->addToolbar();
            $this->sidebar = JHtmlSidebar::render();
        }else{
            $this -> addToolbarModal();
        }

        parent::display($tpl);
    }

    protected function addToolbarModal(){

        TZ_Portfolio_Plus_Addon_MusicHelperToolbar::script('addonMusicGetData();', 'check',
            '', 'COM_TZ_PORTFOLIO_PLUS_INSERT');

//        $bar    = JToolbar::getInstance('toolbar');
//        $text   = JText::_('COM_TZ_PORTFOLIO_PLUS_INSERT');
//        $id     = 'addon-music-toolbar__script';
//
//        $layout = new JLayoutFile('toolbar.script');
//        $html   = $layout->render(array('script' => 'addonMusicGetData', 'text' => $text, 'icon' => 'check'));
//        $bar -> appendButton('Custom', $html, $id);
    }

    protected function addToolbar(){
        $canDo	= JHelperContent::getActions('com_tz_portfolio_plus');
        $user   = JFactory::getUser();

        if ($canDo->get('core.create') ) {
            JToolBarHelper::addNew('song.add');
        }

        JToolBarHelper::editList('song.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publish('songs.publish', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::unpublish('songs.unpublish', 'JTOOLBAR_UNPUBLISH', true);

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'songs.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }
        elseif ($canDo->get('core.edit.state')) {
            JToolBarHelper::trash('songs.trash');
            JToolBarHelper::divider();
        }

        if ($user->authorise('core.admin', 'com_tz_portfolio_plus') || $user->authorise('core.options', 'com_tz_portfolio_plus'))
        {
            $addonId    = $this -> state -> get($this -> getName().'.addon_id');
            JToolbarHelper::link('index.php?option=com_tz_portfolio_plus&view=addon&layout=edit&id='.$addonId
                .'&return='.base64_encode('index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id='.$addonId
                    .'&addon_view='.$this -> getName()), 'JTOOLBAR_OPTIONS','options');
        }
    }
}