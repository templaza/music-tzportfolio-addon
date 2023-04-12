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

use Joomla\CMS\Filesystem\File;
use Joomla\Utilities\ArrayHelper;

class PlgTZ_Portfolio_PlusContentMusic extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;
    protected $data_manager = true;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        JLoader::import('content.music.includes.defines', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH);
    }

    public function onAddContentType()
    {

        $type = array();
        $type_layout = new stdClass();
        $lang = JFactory::getLanguage();

        // Create comment's count type
        $lang_key = 'PLG_' . $this->_type . '_' . $this->_name . '_COUNT_SONG';
        $lang_key = strtoupper($lang_key);
        if ($lang->hasKey($lang_key)) {
            $type_layout->text = JText::_($lang_key);
        } else {
            $type_layout->text = $this->_name;
        }

        $type_layout->value = $this->_name . ':countsong';
        $type[] = clone($type_layout);

        // Create comment type
        $lang_key = 'PLG_' . $this->_type . '_' . $this->_name . '_TITLE';
        $lang_key = strtoupper($lang_key);

        if ($lang->hasKey($lang_key)) {
            $type_layout->text = JText::_($lang_key);
        } else {
            $type_layout->text = $this->_name;
        }

        $type_layout->value = $this->_name;

        $type[] = clone($type_layout);


        // create event
        $lang_key = 'PLG_' . $this->_type . '_' . $this->_name . '_CUSTOM_LINK';
        $lang_key = strtoupper($lang_key);
        if ($lang->hasKey($lang_key)) {
            $type_layout->text = JText::_($lang_key);
        } else {
            $type_layout->text = $this->_name;
        }

        $type_layout->value = $this->_name . ':customlink';
        $type[] = clone($type_layout);

        // create move lists albums page
        $lang_key = 'PLG_' . $this->_type . '_' . $this->_name . '_BACK';
        $lang_key = strtoupper($lang_key);
        if ($lang->hasKey($lang_key)) {
            $type_layout->text = JText::_($lang_key);
        } else {
            $type_layout->text = $this->_name;
        }

        $type_layout->value = $this->_name . ':back';
        $type[] = clone($type_layout);
        return $type;
    }

    public function onAfterDisplayAdditionInfo($context, &$article, $params, $page = 0, $layout = null)
    {
        list($extension, $vName) = explode('.', $context);

        $item = $article;

        if (isset($article->id)) {

            $this->setVariable('songs', '');

            if($__model  = $this -> getModel('Music', 'PlgTZ_Portfolio_PlusContentModel',
                array('ignore_request' => true, 'client' => 'site'))){
                $__model -> setState('filter.contentid', $article -> id);
                $__model -> setState('list.music_order', $params -> get('music_order', 'rdate'));
                if($songs = $__model -> getItems()) {
                    $this->setVariable('songs', $songs);
                }
            }

        }

        if ($extension == 'module' || $extension == 'modules') {
            if ($path = $this->getModuleLayout($this->_type, $this->_name, $extension, $vName, $layout)) {
                // Display html
                ob_start();
                include $path;
                $html = ob_get_contents();
                ob_end_clean();
                $html = trim($html);
                return $html;
            }
        }elseif(in_array($context, array('com_tz_portfolio_plus.portfolio', 'com_tz_portfolio_plus.date'
        , 'com_tz_portfolio_plus.featured', 'com_tz_portfolio_plus.tags', 'com_tz_portfolio_plus.users'))){
            if($html = $this -> _getViewHtml($context,$item, $params, 'count')){
                return $html;
            }
        }
    }

    public function onContentDisplayArticleView($context, &$article, $params, $page = 0, $layout = null){
        return parent::onContentDisplayArticleView($context, $article, $params, $page, $layout);
    }

    /*
     * Register form with position to article form
     * @article: the article data.
     * Return form with position:
     *  -title: title of form to display in article form
     *  -html: html of form to display in article form
     *  -position: position (description, before_description or after_description) to display in article form
     * */
    public function onAddFormToArticleDescription($article = null){

        $position   = $this -> __addFormToPosition($article);

        if(!isset($position -> html)){
            $position -> html   = '';
        }
        return $position;
    }

    public function onContentDisplayListView($context, &$article, $params, $page = 0, $layout = 'default', $module = null){}

    protected function __addFormToPosition($article = null, $position = 'before_description'){
        $_position  = new stdClass();
        $lang       = JFactory::getApplication() -> getLanguage();
        $lang_key   = 'PLG_' . $this->_type . '_' . $this->_name . '_TITLE';
        $lang_key   = strtoupper($lang_key);
        $model      = null;
        $this -> form   = null;

        if ($lang->hasKey($lang_key)) {
            $_position -> title = JText::_($lang_key);
        } else {
            $_position -> title = $this->_name;
        }

        $_position -> addon  = $this->_name;
        $_position -> group  = $this->_type;

        $_position -> position   = $position;

        if($model = $this -> getModel($this -> _name, 'TZ_Portfolio_Plus_Addon_'.ucfirst($this -> _name).'Model')) {
            // Get addon info
            $addon      = TZ_Portfolio_PlusPluginHelper::getPlugin($this -> _type, $this -> _name);

            $table  = $model -> getTable();
            if($table -> load(array('extension_id' => $addon -> id, 'content_id' => $article -> id))) {
                $model->setState($this->_name . '.id', (int)$table->get('id'));

                $properties = $table->getProperties(1);
                $data = ArrayHelper::toObject($properties, '\JObject');

                if($data && isset($data -> value) && is_string($data -> value)){
                    $data -> value  = json_decode($data -> value);
                }
            }

            $path           = TZ_Portfolio_PlusPluginHelper::getLayoutPath($this -> _type, $this -> _name, 'admin');

            if(method_exists($model, 'getForm')) {
                $this->form = $model->getForm();

                $this->form ->loadFile(COM_TZ_PORTFOLIO_PLUS_ADDON_PATH.'/'.$this -> _type.'/'.$this -> _name
                    .'/admin/models/forms/'.$this -> _name.'.xml', false);
                $_data   = new stdClass();
                $_data -> addon  = new stdClass();
                $_data->addon->{$this->_name} = new stdClass();
                if(isset($data)) {
                    $_data->addon->{$this->_name} = $data->value;

                }
                // Get songs by article id
                if($songs = TZ_Portfolio_Plus_Addon_MusicHelper::getSongsByAlbumId($article -> id)){
//                    var_dump(ArrayHelper::getColumn($songs, 'id')); die(__FILE__);
                    $_data->addon->{$this->_name} -> songs  = ArrayHelper::getColumn($songs, 'id');
//                    $_data['songs'] = ArrayHelper::getColumn($songs, 'id');
                }


                $this -> form -> bind($_data);
            }

            $this -> item   = $article;
            if(File::exists($path) && isset($this -> form) && $this -> form) {
                ob_start();
                require $path;
                $content = ob_get_contents();
                ob_end_clean();
                $_position -> html = $content;
            }

        }

        return $_position;
    }


    public function onContentAfterSave($context, $data, $isnew){
        if($context == 'com_tz_portfolio_plus.article' || $context == 'com_tz_portfolio_plus.form') {

            if($model  = $this -> getModel($this -> _name, 'TZ_Portfolio_Plus_Addon_'.$this -> _name.'Model')) {
                if(method_exists($model,'save')) {
//                    if(isset($this -> _myFormDataBeforeSave) && !empty($this -> _myFormDataBeforeSave)){

                        $addon      = TZ_Portfolio_PlusPluginHelper::getPlugin($this -> _type, $this -> _name);
                        $mydata     = array('value' => $this -> _myFormDataBeforeSave);

                        if(!empty($addon) && isset($addon -> id)){
                            $mydata['extension_id'] = $addon -> id;
                            if(!empty($data) && isset($data -> id)){
                                $mydata['content_id']   = $data -> id;
                            }
                            $mydata['published']    = 1;

                            $model->save( $mydata);
                        }
//                    }
                }
            }
        }

    }
}