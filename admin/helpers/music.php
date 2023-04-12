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

class TZ_Portfolio_Plus_Addon_MusicHelper{

    protected static $cache = array();

    public static function addSubmenu($vName)
    {
        TZ_Portfolio_PlusHtmlSidebar::addEntry(JText::_('PLG_CONTENT_MUSIC_SONGS'),'addon_view=songs',$vName == 'songs');
    }

    public static function getAlbumBySongId($id, $addon_element = 'song'){
        $storeId    = __METHOD__;
        $storeId   .= ':'.(int) $id;
        $storeId   .= ':'.$addon_element;
        $storeId    = md5($storeId);

        if(isset(static::$cache[$storeId])){
            return static::$cache[$storeId];
        }

        if(!$id){
            return false;
        }

        $addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');
        $db     = JFactory::getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('DISTINCT c.*');
        $query -> from('#__tz_portfolio_plus_content c');
        $query -> join('INNER', '#__tz_portfolio_plus_addon_data AS d ON FIND_IN_SET(c.id, substring_index(substring_index(value, '
            .$db -> quote('"contentid":"').', -1), '.$db -> quote('"').',1)) AND d.element = '.$db -> quote($addon_element));
        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');
        $query -> where('d.id ='.(int) $id);
        $query -> where('e.id ='.$addon -> id);
        $query -> where('d.element ='.$db -> quote('song'));

        $db -> setQuery($query);

        if($data = $db -> loadObjectList()){
            static::$cache[$storeId]    = $data;
            return $data;
        }

        return false;

    }

    /**
     * Get song by id
     * @param int $id An optional of song
     * */
    public static function getSongById($id, $addon_element = 'song'){
        $storeId    = __METHOD__;
        $storeId   .= ':'.(int) $id;
        $storeId   .= ':'.$addon_element;
        $storeId    = md5($storeId);

        if(isset(static::$cache[$storeId])){
            return static::$cache[$storeId];
        }

        if(!$id){
            return false;
        }

        $addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');
        $db     = JFactory::getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('DISTINCT d.*');
        $query -> from('#__tz_portfolio_plus_addon_data AS d');
//        $query -> join('INNER', '#__tz_portfolio_plus_addon_data AS d ON FIND_IN_SET(c.id, substring_index(substring_index(value, '
//            .$db -> quote('"contentid":"').', -1), '.$db -> quote('"').',1)) AND d.element = '.$db -> quote($addon_element));
        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');
        $query -> where('d.id ='.(int) $id);
        $query -> where('e.id ='.$addon -> id);
        $query -> where('d.element = '.$db -> quote($addon_element));

        $db -> setQuery($query);

        if($data = $db -> loadObject()){
            static::$cache[$storeId]    = $data;
            return $data;
        }

        return false;

    }
    /**
     * Get song by id
     * @param array $ids An optional of song
     * */
    public static function getSongByIds($ids, $addon_element = 'song'){
        $storeId    = __METHOD__;
        $storeId   .= ':'. serialize($ids);
        $storeId   .= ':'.$addon_element;
        $storeId    = md5($storeId);

        if(isset(static::$cache[$storeId])){
            return static::$cache[$storeId];
        }

        if(!$ids){
            return false;
        }

        $addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');
        $db     = JFactory::getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('DISTINCT d.*');
        $query -> from('#__tz_portfolio_plus_addon_data AS d');
//        $query -> join('INNER', '#__tz_portfolio_plus_addon_data AS d ON FIND_IN_SET(c.id, substring_index(substring_index(value, '
//            .$db -> quote('"contentid":"').', -1), '.$db -> quote('"').',1)) AND d.element = '.$db -> quote($addon_element));
        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');
        $query -> where('d.id IN('.implode(',', $ids).')');
        $query -> where('e.id ='.$addon -> id);
        $query -> where('d.element = '.$db -> quote($addon_element));

        $db -> setQuery($query);

        if($data = $db -> loadObjectList()){
            static::$cache[$storeId]    = $data;
            return $data;
        }

        return false;

    }
    /**
     * Get song by album id (article id)
     * @param int $ids An optional of article
     * */
    public static function getSongsByAlbumId($id, $addon_element = 'song'){
        $storeId    = __METHOD__;
        $storeId   .= ':'. $id;
        $storeId   .= ':'.$addon_element;
        $storeId    = md5($storeId);

        if(isset(static::$cache[$storeId])){
            return static::$cache[$storeId];
        }

        if(!$id){
            return false;
        }

        $addon  = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'music');
        $db     = JFactory::getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('DISTINCT d.*');
        $query -> from('#__tz_portfolio_plus_addon_data AS d');
        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');
        $query -> where('e.id ='.$addon -> id);
        $query -> where('d.element = '.$db -> quote($addon_element));

        $query->where('FIND_IN_SET( ' . $id
            . ', substring_index( substring_index( d.value, '
            . $db->quote('"contentid":"') . ', -1 ) , '
            . $db->quote('"') . ', 1 ) ) >0');

        $db -> setQuery($query);

        if($data = $db -> loadObjectList()){
            static::$cache[$storeId]    = $data;
            return $data;
        }

        return false;

    }
}