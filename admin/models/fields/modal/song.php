<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015-2017 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

JLoader::import('defines',JPATH_ADMINISTRATOR.'/components/com_tz_portfolio_plus/includes');
JFormHelper::loadFieldClass('checkboxes');
JFormHelper::loadFieldClass('list');

JLoader::register('TZ_Portfolio_PlusHelperAddon_Datas', JPATH_ADMINISTRATOR.'/components/com_tz_portfolio_plus/helpers/addon_datas.php');

class JFormFieldModal_Song extends JFormFieldCheckboxes
{
    protected $type             = 'Modal_Song';
    protected $layout           = 'form.field.modals.song';

    protected function getRenderer($layoutId = 'default')
    {
        $renderer = parent::getRenderer($layoutId);

        if($renderer){
            $renderer -> addIncludePath(PLG_CONTENT_MUSIC_ADMIN_LAYOUT_PATH);
        }

        return $renderer;
    }

    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        $addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');

        $data['filter_field']   = '';
        $data['iframe_append']   = false;
        $data['filter_field_id']   = '';

        $data['modalId']            = 'add-on-modal__music-'.$this -> id;

        $canDo	= JHelperContent::getActions('com_tz_portfolio_plus');
        $allowNew = filter_var($this -> getAttribute('new', false), FILTER_VALIDATE_BOOLEAN);
        if (!$canDo->get('core.create')) {
            $allowNew  = false;
        }

        $allowEdit = filter_var($this -> getAttribute('edit', false), FILTER_VALIDATE_BOOLEAN);
        if (!$canDo->get('core.edit.state') && !$canDo->get('core.edit.state.own')) {
            $allowEdit  = false;
        }

        if($allowNew){
            $data['link_new']   = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addon -> id)
                .'&addon_layout=modal&tmpl=component&addon_task=song.add';
        }

        $data['allowNew']   = $allowNew;
        $data['allowEdit']  = $allowEdit;

        $data['items']  = false;

        if(!empty($this -> value) && ($items  = TZ_Portfolio_Plus_Addon_MusicHelper::getSongByIds((array) $this -> value))){
            $data['items'] = $items;
        }

        $data['function']           = 'addonMusicSelect__'.$this -> id;

        $data['link']           = TZ_Portfolio_PlusHelperAddon_Datas::getRootURL($addon -> id)
            .'&tmpl=component&addon_layout=modal'.($this -> multiple?'&ismultiple=true':'')
            .'&function='.$data['function'];

        return $data;
    }
}