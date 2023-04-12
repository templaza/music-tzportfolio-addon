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

class TZMusic
{
    public static function getNumberSong($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(DISTINCT d.id)');
        $query->from($db->quoteName('#__tz_portfolio_plus_addon_data').' AS d');
        $query -> join('INNER', '#__tz_portfolio_plus_content AS c ON FIND_IN_SET(c.id, substring_index(substring_index(d.value, '
            .$db -> quote('"contentid":"').', -1), '.$db -> quote('"').',1)'.')');
        $query ->join('INNER', '#__tz_portfolio_plus_content_category_map AS cm ON cm.contentid = c.id');
        $query ->join('INNER', '#__tz_portfolio_plus_categories AS cc ON cc.id = cm.catid AND cc.published = 1');

        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');
        $query -> where('e.folder = '.$db -> quote('content'));
        $query -> where('e.element = '.$db -> quote('music'));
        $query -> where('e.published = 1');

        $query -> where('c.id ='.$id);
        $query -> where('d.element ='.$db -> quote('song'));
        $query -> where('d.published = 1');

        $db->setQuery($query);
        if($song_item = $db->loadResult()) {
            return $song_item;
        }
        return 0;
    }
}