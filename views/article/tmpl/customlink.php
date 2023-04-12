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

if($links = $this->item->params->get('music_custom_link')):
?>
<button class="btn btn-danger music-button" data-toggle="modal" data-target="#music-list-buy-now"><?php echo JText::_('TZ_ADDON_MUSIC_BUTTON_BUY_NOW'); ?></button>
<div id="music-list-buy-now" class="modal fade music-modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-body music-list-buy-now">
                <?php foreach ($links as $i => $link) {
                    $link   = json_decode($link);
                    ?>
                <a class="label label-warning music-link" href="<?php echo $link -> link; ?>">
                    <?php echo $link -> title; ?>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
endif;