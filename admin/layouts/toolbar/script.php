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

// no direct access
defined('_JEXEC') or die;

$script = $displayData['script'];
$text   = $displayData['text'];
$icon   = $displayData['icon'];
?>
<a href="javascript:void(0)" onclick="<?php echo $script; ?>">
    <span class="fa fa-<?php echo $icon; ?> mr-1" aria-hidden="true"></span>
    <?php echo $text; ?>
</a>
