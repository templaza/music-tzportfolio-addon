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

defined('_JEXEC') or die;

JLoader::import('defines',JPATH_ADMINISTRATOR.'/components/com_tz_portfolio_plus/includes');
JFormHelper::loadFieldClass('checkboxes');

/**
 * Supports a modal article picker.
 */
class JFormFieldModal_Songs extends JFormFieldCheckboxes
{
    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Modal_Songs';

    /**
     * Method to get the field input markup.
     *
     * @return	string	The field input markup.
     * @since	1.6
     */
    protected function getInput()
    {
        $allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
        $allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

        if(!COM_TZ_PORTFOLIO_PLUS_JVERSION_4_COMPARE) {
            // Load the modal behavior script.
            JHtml::_('behavior.modal', 'a.modal');
        }

        // Build the script.
        $script = array();
        $script[] = '	function '.$this->id.'Remove(obj) {';
        $script[] = '	    obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);';
        $script[] = '		var tztable = document.getElementById("'.$this->id.'_table");';
        $script[] = '		var tbody = tztable.getElementsByTagName("tbody");';
        $script[] = '		if(!tbody[0].innerHTML.trim().length){
                                var tzclear = document.getElementById("' . $this->id . '_clear");
                                tzclear.setAttribute("class",tzclear.getAttribute("class")+" hidden");
                            }';
        $script[] = '	};';

        $script[] = '	function jSelectSong_'.$this->id.'(ids, titles, addonId) {';
        $script[] = '		var tztable = document.getElementById("'.$this->id.'_table");';
        $script[] = '		var tbody = tztable.getElementsByTagName("tbody");';
        $script[] = '		var parser = new DOMParser();';
        $script[] = '		if(ids.length){';
        $script[] = '		for(var i = 0; i < ids.length; i++){
                                var tr = document.createElement("tr");

                                var td = document.createElement("td");
                                td.innerHTML = titles[i];
                                tr.appendChild(td);';
        $script[] =             'tbody[0].appendChild(tr);';


        $script[] = '           td = td.cloneNode(true);
                                td.className = "btn-group";
                                td.style     = "display: table-cell; position: inherit;";';

        $button     = null;
        // Edit article button
        if ($allowEdit)
        {
            $button .= '<a class=\"btn btn-small hasTooltip\" target=\"_blank\"'
                .' data-toggle=\"tooltip\" title=\"' . JText::_('JACTION_EDIT') . '\"'
                .' href=\"index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id="+addonId+"&addon_task=song.edit&id="+ids[i]+"\">'
                .'<span class=\"icon-edit\"></span></a>';
        }
        $button .= '<a href=\"javascript:\" class=\"btn btn-danger btn-small hastooltip\"'
            .' title=\"'.JText::_('JTOOLBAR_REMOVE').'\" data-toggle=\"tooltip\"'
            .' onclick=\"'.$this->id.'Remove(this);\"><i class=\"icon-trash\"></i></a>';

        $script[]   =       'td.innerHTML = "'.$button.'";';
        $script[]   =       'tr.appendChild(td);';


        $script[] =            'td = td.cloneNode(true);
                                td.className = "";
                                td.style     = "";
                                td.innerHTML = ids[i]+"<input type=\"hidden\" name=\"'.$this -> name.'\"'
            .' id=\"'.$this -> id.'\" value=\""+ids[i]+"\">";
                                tr.appendChild(td);
                                
                                tbody[0].appendChild(tr);

                            }';
        $script[] = '       }';

        if ($allowClear)
        {
            $script[] = '		var tzclear = document.getElementById("' . $this->id . '_clear");';
            $script[] = '		if(tzclear.getAttribute("class").match(/(.*?)\shidden\s?(.*?)/)){
                                    tzclear.setAttribute("class",tzclear.getAttribute("class").replace(/\shidden/,""));
                                };';
        }

        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';

        // Clear button script
        static $scriptClear;

        if ($allowClear && !$scriptClear){

            $scriptClear = true;

            $script[] = '	function jClearSong(id) {';
            $script[] = '	    var tztable = document.getElementById(id+"_table");';
            $script[] = '		var tbody = tztable.getElementsByTagName("tbody");';
            $script[] = '		tbody[0].innerHTML = "";';
            $script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
            $script[] = '		if (document.getElementById(id + "_edit")) {';
            $script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
            $script[] = '		}';
            $script[] = '		return false;';
            $script[] = '	}';
        }

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));


        // Setup variables for display.
        $html	= array();
        $plugin = TZ_Portfolio_PlusPluginHelper::getPlugin('content','music');
        $link	= 'index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id='.$plugin -> id
            .'&addon_view=songs&amp;addon_layout=modals&amp;tmpl=component&amp;function=jSelectSong_'.$this->id;

        if (isset($this->element['language']))
        {
            $link .= '&amp;forcedLanguage=' . $this->element['language'];
        }

        $db	= JFactory::getDBO();
        $db->setQuery(
            'SELECT title' .
            ' FROM #__tz_portfolio_plus_content' .
            ' WHERE id = '.(int) $this->value
        );
        $title = $db->loadResult();


        if (method_exists($db, 'getErrorMsg') && ($error = $db->getErrorMsg())) {
            JError::raiseWarning(500, $error);
        }

        if (empty($title)) {
            $title = JText::_('PLG_CONTENT_MUSIC_SELECT_A_SONG');
        }
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        // The current user display field.
        // The current tag display field.
        $html[] = '<div class="input-append">';

//        $html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';

        $title      = JText::_('PLG_CONTENT_MUSIC_CHANGE_SONGS');
        $textLink   = '<i class="icon-copy"></i>&nbsp;'.JText::_('PLG_CONTENT_MUSIC_SELECT_SONGS');
        $class      = 'modal btn';

        // The active article id field.
        $value  = $this -> value;

        // The user select button.
        $html[] = '	<a class="modal btn" title="'.$title.'"'
            .' href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'
            .$textLink.'</a>';

        // Clear article button
        if ($allowClear)
        {
            $html[] = '<a href="javascript:" id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearSong(\'' . $this->id . '\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</a>';
        }

        $html[] = '</div>';

        // class='required' for client side validation
        $class = '';
        if ($this->required) {
            $class = ' class="required modal-value"';
        }

        $html[] = $this ->_getHtml($this -> id,$value);


        return implode("\n", $html);
    }

    protected function _getHtml($id,$values = null){
        ?>
        <?php
        $tbody  = null;
        $old    = null;
        if($values){
            if($items = $this -> _getItems($values)){
                $allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
                ob_start();
                foreach($items as $item){
                    $song   = $item -> value;
                    if(is_string($song)) {
                        $song   = json_decode($song);
                    }
                    ?>
                    <tr>
                        <td><?php echo $song -> title;?></td>
                        <td class="btn-group" style="text-align: center;">
                            <?php if ($allowEdit){ ?>
                                <a class="btn btn-small hasTooltip" target="_blank" data-toggle="tooltip"
                                   title="<?php echo JText::_('JACTION_EDIT')?>"
                                   href="index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id=<?php
                                   echo $item -> extension_id; ?>&addon_task=song.edit&id=<?php
                                   echo $item -> id;?>"><span class="icon-edit"></span></a>
                            <?php }?>
                            <a href="javascript:" class="btn btn-danger btn-small hasTooltip" data-toggle="tooltip"
                               title="<?php echo JText::_('JTOOLBAR_REMOVE');?>"
                               onclick="<?php echo $id;?>Remove(this);"><i class="icon-trash"></i></a>
                        </td>
                        <td>
                            <?php echo $item -> id;?>
                            <input type="hidden" name="<?php echo $this -> name;?>"
                                   value="<?php echo $item -> id;?>">
                        </td>
                    </tr>
                    <?php
                }
                $tbody  = ob_get_contents();
                ob_end_clean();
            }
        }
        ?>
        <?php
        ob_start();
        ?>
        <div class="clearfix"></div>
        <div style="max-height: 200px; overflow-y: auto;">
            <table id="<?php echo $id.'_table';?>" class="table table-striped">
                <thead>
                <tr>
                    <th><?php echo JText::_('JGLOBAL_TITLE');?></th>
                    <th style="text-align:center; width: 10%;"><?php echo JText::_('JSTATUS');?></th>
                    <th style="width: 5%;"><?php echo JText::_('JGRID_HEADING_ID');?></th>
                </tr>
                </thead>
                <tbody>
                <?php echo $tbody;?>
                </tbody>
            </table>
        </div>

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    protected function _getItems($ids){
        if($ids){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__tz_portfolio_plus_addon_data'));
            $query->where('id IN('.$ids.')');
            $db->setQuery($query);
            if($rows = $db -> loadObjectList()){
                return $rows;
            }
        }
        return false;
    }
}
