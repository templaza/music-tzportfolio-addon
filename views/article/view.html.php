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

class PlgTZ_Portfolio_PlusContentMusicViewArticle extends JViewLegacy{
    protected $state        = null;
    protected $item         = null;
    protected $song         = null;
    protected $params       = null;
    protected $addon        = null;
    protected $songs        = null;
    protected $playlist     = array();
    protected $fileTypes    = array('mp3','ogg','mp4');

    public function display($tpl = null){
        $this -> item       = $this -> get('Item');
        $state              = $this -> get('State');
        $params             = $state -> get('params');
        $this -> state      = $state;
        $this -> params     = $params;
        $songs              = $this -> get('Songs');
        $addon              = $state -> get('article.addon');

        if($songs){
            if(count($songs)) {
                $playlist = array();
                foreach ($songs as $i => $song) {
                    if ($song->value && !empty($song->value)) {
                        $playlist[$i] = array();
                        $playlist[$i]['title'] = $song->value->title;
                        if (isset($song->value->thumbnail) && $song->value->thumbnail) {
                            $playlist[$i]['poster'] = JUri::root(true) . '/' . $song->value->thumbnail;
                        }
                        if (isset($song->value->description) && $song->value->description) {
                            $playlist[$i]['description'] = $song->value->description;
                        }else{
                            $playlist[$i]['description'] = '';
                        }
                        if ($listfiles = $song->value->file_names) {
                            foreach ($listfiles as $file) {
                                $ext = JFile::getExt($file);
                                if($ext == 'webm'){
                                    $playlist[$i]['webmv'] = JUri::root() . $file;
                                }else {
                                    $playlist[$i][$ext] = JUri::root() . $file;
                                }
                            }
                        }
                    }
                }
                if(count($playlist) == 1){
                    $this -> song   = $playlist[0];
                }elseif(count($playlist) > 1){
                    $this->playlist = $playlist;
                }
            }
        }

        $this -> songs      = $songs;
        if(isset($addon -> params) && is_string($addon -> params)){
            $addonParams        = $addon -> params;
            $addon -> params    = new JRegistry();
            $addon -> params -> loadString($addonParams);
        }
        $this -> addon  = $addon;

        parent::display($tpl);
    }
}