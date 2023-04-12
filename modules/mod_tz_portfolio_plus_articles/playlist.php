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
if ($songs):
//    var_dump($songs);
    $params = $this->params;
    $doc = JFactory::getDocument();

    $doc->addStyleSheet(TZ_Portfolio_PlusUri::base() . '/addons/content/music/libraries/jPlayer-2.9.2/skin/custom/custom.css');
    $doc->addScript(TZ_Portfolio_PlusUri::base() . '/addons/content/music/libraries/jPlayer-2.9.2/js/jquery.jplayer.min.js');
    $doc->addScript(TZ_Portfolio_PlusUri::base() . '/addons/content/music/libraries/jPlayer-2.9.2/add-on/jplayer.playlist.min.js');

    $playlist = array();

    foreach ($songs as $i => $song) {
        if ($song->value && !empty($song->value)) {
            $value = json_decode($song->value);
            $playlist[$i] = array();
            $playlist[$i]['title'] = $value->title;
            $files = explode('|', $value->file_names);
            foreach ($files as $file) {
                $ext = JFile::getExt($file);
                $playlist[$i][$ext] = JUri::root() . $file;
            }
        }
    }
    if (count($playlist)) {
        ?>

        <script type="text/javascript">
            (function ($) {
                //<![CDATA[
                $(document).ready(function () {

                    var myPlaylist = new jPlayerPlaylist({
                        jPlayer: "#jquery_jplayer_N",
                        cssSelectorAncestor: "#jp_container_N"
                    }, <?php echo json_encode($playlist);?>, {
                        playlistOptions: {
                            enableRemoveControls: false
                        },
                        swfPath: "<?php echo TZ_Portfolio_PlusUri::base() . '/addons/content/music/js'?>",
                        supplied: "oga, mp3",
                        useStateClassSkin: true,
                        autoBlur: false,
                        smoothPlayBar: true,
                        keyEnabled: true,
                        audioFullScreen: true
                    });
                });
                //]]>
            })(jQuery);
        </script>
        <div id="jquery_jplayer_N" class="jp-jplayer"></div>
        <div id="jp_container_N" class="jp-audio" role="application" aria-label="media player">
            <div class="jp-type-playlist container">

                <div class="jp-gui jp-interface">
                    <div class="jp-title"></div>
                    <div class="jp-controls">
                        <button class="jp-previous" role="button" tabindex="0">
                            <i class="fa fa-backward"></i>
                        </button>
                        <button class="jp-play" role="button" tabindex="0">
                            <i class="fa fa-play"></i>
                        </button>
                        <button class="jp-next" role="button" tabindex="0">
                            <i class="fa fa-forward"></i>
                        </button>
                        <?php if ($params->get('music_show_stop_btn', 0)) { ?>
                            <button class="jp-stop" role="button" tabindex="0">
                                <i class="fa fa-stop"></i>
                            </button>
                        <?php } ?>
                    </div>

                    <div class="jp-progress">
                        <div class="jp-time-holder">
                            <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                        </div>
                        <div class="tz_progress_bar">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                        </div>
                        <div class="jp-time-holder">
                            <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                        </div>
                    </div>

                    <div class="jp-volume-controls">
                        <div>
                            <button class="jp-mute" role="button" tabindex="0">
                                <i class="fa fa-volume-down" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="tz_progress_bar">
                            <div class="jp-volume-bar">
                                <div class="jp-volume-bar-value"></div>
                            </div>
                        </div>
                        <div>
                            <button class="jp-volume-max" role="button" tabindex="0">
                                <i class="fa fa-volume-up" aria-hidden="true"></i>
                            </button>
                        </div>

                    </div>


                    <div class="jp-toggles">
                        <?php if ($params->get('music_show_repeat', 0)) { ?>
                            <button class="jp-repeat" role="button" tabindex="0">
                                <i class="fa fa-repeat" aria-hidden="true"></i>
                            </button>
                        <?php }
                        if ($params->get('music_show_shuffle', 0)) { ?>
                            <button class="jp-shuffle" role="button" tabindex="0">
                                <i class="fa fa-random" aria-hidden="true"></i>
                            </button>
                        <?php }
                        if ($params->get('music_show_fullscreen', 0)) { ?>
                            <button class="jp-full-screen" role="button" tabindex="0">
                                <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="jp-playlist">
                    <ul>
                        <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                        <li>&nbsp;</li>
                    </ul>
                </div>
                <div class="jp-no-solution">
                    <span>Update Required</span>
                    To play the media you will need to either update your browser to a recent version or update your
                    <a
                        href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                </div>
            </div>
        </div>
        <?php
    }
endif; ?>
