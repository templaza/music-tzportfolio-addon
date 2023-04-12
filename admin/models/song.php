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

use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

class TZ_Portfolio_Plus_Addon_MusicModelSong extends TZ_Portfolio_PlusModelAddon_Data{
    protected $addon_element    = 'song';

    public function getForm($data = array(), $loadData = true)
    {
        $form   = parent::getForm($data, $loadData);

        if($form){
            $input  = JFactory::getApplication() ->input;

            if($input -> get('addon_layout') == 'modal'){
                $form -> removeField('contentid', 'value');
            }

        }

        return $form;
    }

    public function getItem($pk = null){
        if($item = parent::getItem($pk)){
//            $db     = JFactory::getDbo();
//            $query  = $db -> getQuery(true);
//
//            $query -> select

            if(is_string($item -> value)){
                $item -> value  = json_decode($item -> value);
            }
            if(isset($item -> value -> file_names) && !empty($item -> value -> file_names)){
                $item -> value -> file_names  = explode('|', $item -> value -> file_names);
            }
            return $item;
        }
        return false;
    }
    protected function loadFormData()
    {
        if($data = parent::loadFormData()){
            if(isset($data -> value -> file_names) && !empty($data -> value -> file_names)
                && !is_string($data -> value -> file_names)){
                $data -> value -> file_names    = implode('|', $data -> value -> file_names);
            }
            return $data;
        }
        return false;
    }

    public function save($data)
    {
        $content_id = array();
        if(isset($data['value']) && $data['value']){
            if(!isset($data['value']['alias'])
                || (isset($data['value']['alias']) && empty($data['value']['alias']))){
                $data['value']['alias']  = JApplicationHelper::stringURLSafe($data['value']['title']);
            }else{
                $data['value']['alias'] = JApplicationHelper::stringURLSafe($data['value']['alias']);
            }
            if(isset($data['value']['contentid']) && !empty($data['value']['contentid'])){
                $content_id = $data['value']['contentid'];
                if(!is_string($data['value']['contentid'])) {
                    $data['value']['contentid'] = implode(',',$data['value']['contentid']);
                }
//                unset($data['value']['contentid']);
            }
            $input      = JFactory::getApplication() -> input;

            if(isset($data['value']['file_names']) && !empty($data['value']['file_names'])){
                $_fileNames  = explode('|', $data['value']['file_names']);
            }

            // Remove song when them were chosen
            if(isset($data['value']['remove']) && $data['value']['remove']){
                $removes    = $data['value']['remove'];
                $filesFlip  = array_flip($_fileNames);
                if(is_array($removes)){
                    foreach($removes as $remove){
                        if(isset($filesFlip[$remove])){
                            // Delete song file
                            if(JFile::delete(JPATH_SITE.DIRECTORY_SEPARATOR.$remove)) {
                                // Remove song in database
                                unset($_fileNames[$filesFlip[$remove]]);
                            }
                        }
                    }
                    $_fileNames = array_reverse($_fileNames);
                    $data['value']['file_names']    = implode('|',$_fileNames);
                }
            }

            if($files  = $input -> post ->files) {
                if($files = $files->get('jform')) {
                    if($fileNames = $this->upload($files['value']['file'], $data['value']['alias'])){
                        if(isset($_fileNames) && is_array($_fileNames)) {
                            $fileNames  = array_merge($_fileNames, $fileNames);
                            $fileNames  = array_filter($fileNames);
                            $fileNames  = array_unique($fileNames);
                        }
                        if(!empty($data['value']['file_names'])){
                            $data['value']['file_names']    = implode('|',$fileNames);
                        }else {
                            $data['value']['file_names']    = implode('|', $fileNames);
                        }
                    }
                }
            }
        }

        if($data && isset($data['id']) && !$data['id']) {
            list($title, $alias)    = $this -> generateNewTitle(0,$data['value']['alias'],$data['value']['title']);
            $data['value']['title'] = $title;
            $data['value']['alias'] = $alias;
        }

        $result = parent::save($data);

//        $addon_id   = JFactory::getApplication()->input->getInt('addon_id');
//
//        // Store song assigned to article
//        if($result && count($content_id)){
//            $song_id    = $this -> getState($this->getName() . '.id');
//
//            if($song_id){
//
//                $tblMeta    = $this -> getTable('Addon_Meta');
//
//                // Remove all article without assigned
//                $db     = JFactory::getDbo();
//                $query  = $db -> getQuery(true);
//
//                $query -> delete($tblMeta -> getTableName());
//                $query -> where('addon_id ='.$addon_id);
//                $query -> where('data_id ='.$song_id);
//                $query -> where('meta_key ='.$db -> quote($this -> getName()));
//                $query -> where('meta_id NOT IN('.implode(',', $content_id).')');
//
//                $db -> setQuery($query);
//                $db -> execute();
//
//                foreach ($content_id as $id){
//                    $meta_data  = array(
//                        'addon_id'  => $addon_id,
//                        'data_id'   => $song_id,
//                        'meta_id'   => $id,
//                        'meta_key'  => $this -> getName()
//                    );
//                    $tblMeta -> reset();
//                    $tblMeta -> set($tblMeta -> getKeyName(), 0);
//                    $tblMeta -> load($meta_data);
//                    $tblMeta -> bind($meta_data);
//                    $tblMeta -> store();
//                }
//            }
//        }

        return $result;
    }

    protected function checkAlias($alias){
        $db     = $this -> getDbo();
        $query  = $db -> getQuery(true);
        $query -> select('*');
        $query -> from('#__tz_portfolio_plus_addon_data');
        $query -> where('(SUBSTRING(substring_index(value,' . $db->quote('"alias":"') . ',-1),'
            .'1,'.strlen($alias).') = '.$db -> quote($alias).')');
        $db -> setQuery($query);
        if($db -> loadResult()){
            return true;
        }
        return false;
    }


    protected function generateNewTitle($category_id, $alias, $title)
    {
        // Alter the title & alias
        while ($this -> checkAlias($alias))
        {
            $title = StringHelper::increment($title);
            $alias = StringHelper::increment($alias, 'dash');
        }

        return array($title, $alias);
    }

    protected function upload($files, $newFileName){
        if($files){
            $app        = JFactory::getApplication();
            $plugin     = TZ_Portfolio_PlusPluginHelper::getPlugin('content','music');
            $plgParams  = new Registry;
            $plgParams -> loadString($plugin -> params);

            if(is_array($files)){
                // Get some params
                $mime_types     = $plgParams -> get('mime_type','audio/ogg,audio/mpeg,video/mp4,video/ogg,video/webm');
                $mime_types     = explode(',',$mime_types);
                $file_types     = $plgParams -> get('file_type','mp3,ogg,webm,webmv,ogv,m4v');
                $file_types     = explode(',',$file_types);
                $file_sizes     = $plgParams -> get('file_size',10);
                $file_sizes     = $file_sizes * 1024 * 1024;

                $file_names     = array();

                foreach($files as $file) {
                    if(isset($file['name']) && !empty($file['name'])){
                        $file_type = JFile::getExt($file['name']);

                        //-- Check image information --//
                        // Check MIME Type
                        if (!in_array($file['type'], $mime_types)) {
                            $app->enqueueMessage(JText::_('PLG_CONTENT_MUSIC_ERROR_WARNINVALID_MIME'), 'notice');
                            return false;
                        }

                        // Check file type
                        if (!in_array($file_type, $file_types)) {
                            $app->enqueueMessage(JText::_('PLG_CONTENT_MUSIC_ERROR_WARNFILETYPE'), 'notice');
                            return false;
                        }

                        // Check file size
                        if ($file['size'] > $file_sizes) {
                            $app->enqueueMessage(JText::_('PLG_CONTENT_MUSIC_ERROR_WARNFILETOOLARGE'), 'notice');
                            return false;
                        }
                        //-- End check image information --//

                        $folder     = 'media'.DIRECTORY_SEPARATOR.'tz_portfolio_plus'.DIRECTORY_SEPARATOR.'music';
                        if(!JFile::exists(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'index.html')){
                            $html   = htmlspecialchars_decode('<!DOCTYPE html><title></title>');
                            JFile::write(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'index.html', $html);
                        }
                        
                        $newpath    =  $folder. DIRECTORY_SEPARATOR . $newFileName . '.' . $file_type;
                        if (!JFile::upload($file['tmp_name'], JPATH_SITE . DIRECTORY_SEPARATOR . $newpath)) {
                            $app->enqueueMessage(JText::_('PLG_CONTENT_MUSIC_ERROR_WARNFILETOOLARGE'), 'notice');
                            return false;
                        }
                        $file_names[] = str_replace(DIRECTORY_SEPARATOR, '/', $newpath);
                    }
                }
                if(count($file_names)) {
                    return $file_names;
                }
            }
        }
        return false;
    }
}