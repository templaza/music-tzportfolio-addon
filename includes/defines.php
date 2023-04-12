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

if(!defined('PLG_CONTENT_MUSIC_PATH_SITE')) {
    define('PLG_CONTENT_MUSIC_PATH_SITE', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH.DIRECTORY_SEPARATOR.'content'
        .DIRECTORY_SEPARATOR.'music');
}
if(!defined('PLG_CONTENT_MUSIC_ADMIN_PATH')) {
    define ('PLG_CONTENT_MUSIC_ADMIN_PATH', PLG_CONTENT_MUSIC_PATH_SITE.DIRECTORY_SEPARATOR.'admin');
}
if(!defined('PLG_CONTENT_MUSIC_ADMIN_LAYOUT_PATH')) {
    define ('PLG_CONTENT_MUSIC_ADMIN_LAYOUT_PATH', PLG_CONTENT_MUSIC_ADMIN_PATH.DIRECTORY_SEPARATOR.'layouts');
}