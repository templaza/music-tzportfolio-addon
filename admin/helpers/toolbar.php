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

class TZ_Portfolio_Plus_Addon_MusicHelperToolbar{

    protected static $cache = array();

    public static function script($script = '', $icon = '', $iconOver = '', $alt = '', $id=''){

        $bar    = JToolbar::getInstance('toolbar');
        $text   = $alt?JText::_($alt):JText::_('PLG_CONTENT_MUSIC_SCRIPT');
        $id     = $id?$id:'addon-music-toolbar__script';

        $layout = new JLayoutFile('toolbar.script');
        $layout -> addIncludePath(PLG_CONTENT_MUSIC_ADMIN_LAYOUT_PATH);
        $html   = $layout->render(array('script' => $script, 'text' => $text, 'icon' => $icon));
        $bar -> appendButton('Custom', $html, $id);
    }
}