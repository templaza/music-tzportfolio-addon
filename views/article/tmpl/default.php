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

if ($this -> params -> get('music_show_article', 1) && $songs = $this->songs):
    $doc        = JFactory::getDocument();
    $doc->addStyleSheet('components/com_tz_portfolio_plus/addons/content/music/css/style.css');
    $doc->addScript('components/com_tz_portfolio_plus/addons/content/music/libraries/jplayer-2.9.2/js/jquery.jplayer.min.js');
    $doc->addScript('components/com_tz_portfolio_plus/addons/content/music/libraries/jplayer-2.9.2/add-on/jplayer.playlist.min.js');

    if($this -> params -> get('music_enable_glyphicon', 1)){
        $doc -> addStyleSheet('components/com_tz_portfolio_plus/addons/content/music/css/glyphicons.css');
    }

//    var_dump($this -> params);
////    var_dump($this -> state -> get('article.addon'));
//    die();

    if(count($this -> songs) == 1){
        echo $this -> loadTemplate('single');
    }elseif(count($this -> songs) > 1){
        echo $this -> loadTemplate('playlist');
    }
endif; ?>