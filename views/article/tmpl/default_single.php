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

if($song = $this -> song):
    $params     = $this -> params;
?>
<script type="text/javascript">
    (function ($) {
        //<![CDATA[
        $(document).ready(function () {
            $("#jquery_jplayer_1").jPlayer({
                ready: function(event){
                    $(this).jPlayer("setMedia", <?php echo json_encode($song); ?>);
                    <?php if($params -> get('music_auto_play',0)){?>
                    $(this).jPlayer("play");
                    <?php }?>
                },
                volume: <?php echo ((float) $params -> get('music_volume', 0.8));?>,
                swfPath: "<?php echo 'components/com_tz_portfolio_plus/addons/content/music/libraries/jplayer-2.9.2/js'?>",
                supplied: "<?php echo $params -> get('file_type','mp3,ogg,webm,webmv,ogv,m4v');?>",
                useStateClassSkin: true,
                size :{
                    width: "100%",
                    height: "auto"
                },
                cssSelector: {
                    description: ".jp-description"
                },
                smoothPlayBar: true,
                keyEnabled: true,
                audioFullScreen: false
            });
            $(document).on($.jPlayer.event.setmedia, function(jpEvent) {
                var player = jpEvent.jPlayer,
                    options = player.options,
                    status  = player.status;
                if(!player.status.media.poster && !player.status.video){
                    $(jpEvent.target).css("display", "none");
                    $(player.options.cssSelector.fullScreen).css("display", "none");
                }else{
                    $(jpEvent.target).css("display", "");
                    $(player.options.cssSelector.fullScreen).css("display", "");
                }

                if(status.media.description.length){
                    $(options.cssSelectorAncestor + " " +options.cssSelector.description).html(status.media.description);
                }else{
                    $(options.cssSelectorAncestor + " " +options.cssSelector.description).css("display","none");
                }
            });
        });
    })(jQuery);
</script>

<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <div class="jp-controls">
                <button class="jp-play" role="button" tabindex="0">
                    <i class="play glyphicon glyphicon-play"></i>
                    <i class="pause glyphicon glyphicon-pause"></i>
                </button>
                <?php if ($params->get('music_show_stop_btn', 1)) { ?>
                    <button class="jp-stop" role="button" tabindex="0">
                        <i class="glyphicon glyphicon-stop"></i>
                    </button>
                <?php } ?>
            </div>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
                <div class="jp-time-holder">
                    <span class="jp-current-time" role="timer" aria-label="time"></span>
                    <span class="jp-separator">/</span>
                    <span class="jp-duration" role="timer" aria-label="duration"></span>
                </div>
            </div>

            <div class="jp-volume-controls">
                <button class="jp-mute" role="button" tabindex="0">
                    <i class="volume-down glyphicon glyphicon-volume-down" aria-hidden="true"></i>
                    <i class="volume-muted glyphicon glyphicon-volume-off" aria-hidden="true"></i>
                </button>
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                </div>
                <button class="jp-volume-max" role="button" tabindex="0">
                    <i class="glyphicon glyphicon-volume-up" aria-hidden="true"></i>
                </button>

            </div>

            <?php if ($params->get('music_show_repeat', 0) or $params->get('music_show_fullscreen', 0)) { ?>
                <div class="jp-toggles">
                    <?php if ($params->get('music_show_repeat', 0)) { ?>
                        <button class="jp-repeat" role="button" tabindex="0">
                            <i class="glyphicon glyphicon-repeat" aria-hidden="true"></i>
                        </button>
                    <?php }
                    if ($params->get('music_show_fullscreen', 0)) { ?>
                        <button class="jp-full-screen" role="button" tabindex="0">
                            <i class="fullscreen glyphicon glyphicon-fullscreen" aria-hidden="true"></i>
                            <i class="exit-fullscreen glyphicon glyphicon-resize-small" aria-hidden="true"></i>
                        </button>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
        <?php if($params -> get('music_show_title',1) or $params -> get('music_show_description',1)){?>
        <div class="jp-details">
            <?php if($params -> get('music_show_title',1)){?>
            <div class="jp-title" aria-label="title">&nbsp;</div>
            <?php }
            if($params -> get('music_show_description',1)){?>
            <div class="jp-description" aria-label="description">&nbsp;</div>
            <?php }?>
        </div>
        <?php }?>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>
<?php
endif; ?>