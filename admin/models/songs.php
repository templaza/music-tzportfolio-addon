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

class TZ_Portfolio_Plus_Addon_MusicModelSongs extends TZ_Portfolio_PlusModelAddon_Datas{
    protected $addon_element   = 'song';

    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'extension_id',
                'type', 'd.type',
                'published', 'd.published',
                'value',
                'value.title',
                'value.thumbnail',
                'ordering', 'd.ordering',
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'd.id', $direction = 'desc'){

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    public function getListQuery(){
        if($query = parent::getListQuery()){
            $db     = $this -> getDbo();

            // Filter by search in title, alias or id.
            $search = $this->getState('filter.search');
            if (!empty($search)) {
                if (stripos($search, 'id:') === 0) {
                    $query->where('id = '.(int) substr($search, 3));
                }
                elseif (stripos($search, 'alias:') === 0) {
                    $search = $db->escape(substr($search, 6));
                    $query -> where('(SUBSTRING(substring_index(value,' . $db->quote('"alias":"') . ',-1),'
                        .'1,'.strlen($search).') = '.$db -> quote($search).')');
                }
                else {
                    $query -> where('(SUBSTRING(substring_index(value,' . $db->quote('"title":"') . ',-1),'
                        .'1,'.strlen($search).') = '.$db -> quote($search).')');
                }
            }

            $query -> group('d.id');

            return $query;
        }
        return false;
    }

    public function getItems()
    {
        $items = parent::getItems();

        if($items){
            foreach($items as &$item){
                $item -> albums = array();
//                if($item -> id == 24){
//                    var_dump(TZ_Portfolio_Plus_Addon_MusicHelper::getAlbumBySongId($item -> id)); die();
//                }
                if($albums = TZ_Portfolio_Plus_Addon_MusicHelper::getAlbumBySongId($item -> id)){
                    $item -> albums = $albums;
                }
            }
        }

        return $items;
    }
}