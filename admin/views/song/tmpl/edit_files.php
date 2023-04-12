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

if($files = $this -> files):
    $group  = 'value';
?>
    <table class="table table-bordered" style="margin-top: 15px;">
        <thead>
        <tr>
            <th>File</th>
            <th width="5%"><?php echo JText::_('JTOOLBAR_REMOVE');?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($files as $i => $file){?>
        <tr>
            <td>
                <span class="icon-file"></span> <?php echo basename($file);?>
            </td>
            <td class="center">
                <?php echo $this -> form -> getInput('remove',$group, $file);?>
            </td>
        </tr>
        <?php }?>
        </tbody>
    </table>
<?php
endif;