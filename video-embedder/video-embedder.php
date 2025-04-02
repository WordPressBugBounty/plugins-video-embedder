<?php /*
Plugin Name: Video Embedder
Plugin URI: http://www.gate303.net/2007/12/17/video-embedder/
Description: This allows you to embed videos from various sources in your blog without breaking validation. For settings and more information, visit <a href="options-general.php?page=video-embedder.php">Options/Video Embedder</a>.
Version: 1.8
Author: Kristoffer Forsgren
Author URI: http://www.gate303.net/
License: GPL2

Copyright 2007  Kristoffer Forsgren */

if (!defined('ABSPATH')) {
    exit;
}

define('VIDEOEMBEDDER_VERSION', '1.8');

add_filter('the_content', 'videoembedder_embed');
add_action('admin_menu', 'videoembedder_add_pages');

function videoembedder_add_pages() {
    add_options_page(
        'Video Embedder Options',
        'Video Embedder',
        'manage_options',
        'video-embedder.php',
        'videoembedder_options_page'
    );
}

function videoembedder_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html(__('You do not have sufficient permissions to access this page.', 'video-embedder')));
    }

    if (isset($_POST['videoembedder_nonce']) && wp_verify_nonce(sanitize_key($_POST['videoembedder_nonce']), 'videoembedder_options')) {
        $options = array(
            "version" => VIDEOEMBEDDER_VERSION,
            "video_width" => isset($_POST["video_width"]) && !empty($_POST["video_width"]) ? sanitize_text_field(wp_unslash($_POST["video_width"])) : getDefaultOptionValue("video_width"),
            "video_height" => isset($_POST["video_height"]) && !empty($_POST["video_height"]) ? sanitize_text_field(wp_unslash($_POST["video_height"])) : getDefaultOptionValue("video_height"),
            "youtube_tag" => isset($_POST["youtube_tag"]) && !empty($_POST["youtube_tag"]) ? sanitize_text_field(wp_unslash($_POST["youtube_tag"])) : getDefaultOptionValue("youtube_tag"),
            "dailymotion_tag" => isset($_POST["dailymotion_tag"]) && !empty($_POST["dailymotion_tag"]) ? sanitize_text_field(wp_unslash($_POST["dailymotion_tag"])) : getDefaultOptionValue("dailymotion_tag"),
            "vimeo_tag" => isset($_POST["vimeo_tag"]) && !empty($_POST["vimeo_tag"]) ? sanitize_text_field(wp_unslash($_POST["vimeo_tag"])) : getDefaultOptionValue("vimeo_tag"),
            "quicktime_tag" => isset($_POST["quicktime_tag"]) && !empty($_POST["quicktime_tag"]) ? sanitize_text_field(wp_unslash($_POST["quicktime_tag"])) : getDefaultOptionValue("quicktime_tag"),
            "windowsmedia_tag" => isset($_POST["windowsmedia_tag"]) && !empty($_POST["windowsmedia_tag"]) ? sanitize_text_field(wp_unslash($_POST["windowsmedia_tag"])) : getDefaultOptionValue("windowsmedia_tag")
        );

        $updated=true;
        update_option('videoembedder_options', $options);
    } else {
        $updated=false;
    }

    $videoembedder_options = get_option('videoembedder_options');

    $height = isset($videoembedder_options["video_height"]) ? $videoembedder_options["video_height"] : getDefaultOptionValue("video_height");
    $width = isset($videoembedder_options["video_width"]) ? $videoembedder_options["video_width"] : getDefaultOptionValue("video_width");
    $youtube_tag = isset($videoembedder_options["youtube_tag"]) ? $videoembedder_options["youtube_tag"] : getDefaultOptionValue("youtube_tag");
    $dailymotion_tag = isset($videoembedder_options["dailymotion_tag"]) ? $videoembedder_options["dailymotion_tag"] : getDefaultOptionValue("dailymotion_tag");
    $vimeo_tag = isset($videoembedder_options["vimeo_tag"]) ? $videoembedder_options["vimeo_tag"] : getDefaultOptionValue("vimeo_tag");
    $quicktime_tag = isset($videoembedder_options["quicktime_tag"]) ? $videoembedder_options["quicktime_tag"] : getDefaultOptionValue("quicktime_tag");
    $windowsmedia_tag = isset($videoembedder_options["windowsmedia_tag"]) ? $videoembedder_options["windowsmedia_tag"] : getDefaultOptionValue("windowsmedia_tag");
?>
    <div class="wrap"><h2>Video Embedder Settings</h2>
        <form name='form' method='post' action=''>
            <?php wp_nonce_field('videoembedder_options', 'videoembedder_nonce'); ?>
            <table class="form-table" width='100%' border='0' cellspacing='0' cellpadding='0'>
                <tr>
                    <th scope="row" width='20%'>Video Aspect Ratio</th>
                    <td width='80%'><input name='video_width' type='text' id='video_width' value='<?php echo esc_html($width); ?>' size="5"> / <input name='video_height' type='text' id='video_height' value='<?php echo esc_html($height); ?>' size="5"> (Default: 16/9)</td>
                </tr>
                <tr>
                    <th scope="row">Youtube tag</th>
                    <td><input name='youtube_tag' type='text' id='youtube_tag' value='<?php echo esc_html($youtube_tag); ?>'> Usage: [<?php echo esc_html($youtube_tag); ?>]video_id[/<?php echo esc_html($youtube_tag); ?>]</td>
                </tr>
                <tr>
                    <th scope="row">Dailymotion tag</th>
                    <td><input name='dailymotion_tag' type='text' id='dailymotion_tag' value='<?php echo esc_html($dailymotion_tag); ?>'> Usage: [<?php echo esc_html($dailymotion_tag); ?>]video_id[/<?php echo esc_html($dailymotion_tag); ?>]</td>
                </tr>
                <tr>
                    <th scope="row">Vimeo tag</th>
                    <td><input name='vimeo_tag' type='text' id='vimeo_tag' value='<?php echo esc_html($vimeo_tag); ?>'> Usage: [<?php echo esc_html($vimeo_tag); ?>]video_id[/<?php echo esc_html($vimeo_tag); ?>]</td>
                </tr>
                <!-- local media -->
                <tr>
                    <th scope="row">Quicktime tag</th>
                    <td><input name='quicktime_tag' type='text' id='quicktime_tag' value='<?php echo esc_html($quicktime_tag); ?>'> Usage: [<?php echo esc_html($quicktime_tag); ?>]URL[/<?php echo esc_html($quicktime_tag); ?>]</td>
                </tr>
                <tr>
                    <th scope="row">Windows Media Player tag</th>
                    <td><input name='windowsmedia_tag' type='text' id='windowsmedia_tag' value='<?php echo esc_html($windowsmedia_tag); ?>'> Usage: [<?php echo esc_html($windowsmedia_tag); ?>]URL[/<?php echo esc_html($windowsmedia_tag); ?>]</td>
                </tr>
            </table>
            <input class="button button-primary button-md" type='submit' name='Submit' value='Save'>
            <?php if ($updated==true) echo ' Settings updated'; ?>
        </form>
    </div>

    <div class="wrap">
        <h2>Help</h2>
        <h3>Youtube help</h3>
        <p>For Youtube movies, check the URL and use the red part: http://www.youtube.com/watch?v=<strong style="color:red;">zORv8wwiadQ</strong></p>
        <p>Type [<?php echo esc_html($youtube_tag); ?>]<strong style="color:red;">zORv8wwiadQ</strong>[/<?php echo esc_html($youtube_tag); ?>] in the editor to embed the video.</p>

        <h3>Dailymotion help</h3>
        <p>For Dailymotion, check the URL and use the red part: http://www.dailymotion.com/video/<strong style="color:red;">xoh8j</strong>_monty-python-dead-parrot-sketch_family</p>
        <p>Type [<?php echo esc_html($dailymotion_tag); ?>]<strong style="color:red;">xoh8j</strong>[/<?php echo esc_html($dailymotion_tag); ?>] in the editor to embed the video.</p>

        <h3>Vimeo help</h3>
        <p>For Vimeo, check the URL and use the red part: http://www.vimeo.com/<strong style="color:red;">367351</strong></p>
        <p>Type [<?php echo esc_html($vimeo_tag); ?>]<strong style="color:red;">367351</strong>[/<?php echo esc_html($vimeo_tag); ?>] in the editor to embed the video.</p>

        <h3>Quicktime help</h3>
        <p>For Quicktime files, enclose the URL</p>
        <p>Type [<?php echo esc_html($quicktime_tag); ?>]<strong style="color:red;">http://www.yoursite.com/path/file.mov</strong>[/<?php echo esc_html($quicktime_tag); ?>] in the editor to embed the video.</p>

        <h3>Windows Media Player help</h3>
        <p>For Windows Media files, enclose the URL</p>
        <p>Type [<?php echo esc_html($windowsmedia_tag); ?>]<strong style="color:red;">http://www.yoursite.com/path/file.wmv</strong>[/<?php echo esc_html($windowsmedia_tag); ?>] in the editor to embed the video.</p>

        <h2>More help</h2>
        <p>Visit the <a href="http://www.gate303.net/2007/12/17/video-embedder/">home page for Video Embedder</a> for more help and support</p>
        <p>Video Embedder version <?php echo esc_html(VIDEOEMBEDDER_VERSION); ?></p>
    </div>
    <?php
}

function videoembedder_embed($content) {
    $tags = get_option('videoembedder_options');


    $height = isset($tags["video_height"]) ? $tags["video_height"] : '9';
    $width = isset($tags["video_width"]) ? $tags["video_width"] : '16';
    $aspect_ratio = esc_html($width) . "/" . esc_html($height);


    // Youtube
    $tag = $tags["youtube_tag"];
    preg_match_all('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', $content, $videocode);
    if (isset($videocode['0']) && is_array($videocode['0'])) {
        for ($i=0; $i < count($videocode['0']); $i++) {
            $video =  $videocode['1'][$i];
            $replace = $videocode['0'][$i];
            $new = '<iframe src="https://www.youtube-nocookie.com/embed/'.esc_html($video).'" style="width: 100%; height: auto; aspect-ratio: '.$aspect_ratio.'; border: none; overflow: hidden;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
            $content = str_replace($replace, $new, $content);
        }
    }

    // Dailymotion
    $tag = $tags["dailymotion_tag"];
    preg_match_all('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', $content, $videocode);
    if (isset($videocode['0']) && is_array($videocode['0'])) {
        for ($i=0; $i < count($videocode['0']); $i++) {
            $video =  $videocode['1'][$i];
            $replace = $videocode['0'][$i];
            $new = '<iframe src="https://geo.dailymotion.com/player.html?video='.esc_html($video).'" style="width: 100%; height: auto; aspect-ratio: '.$aspect_ratio.'; border: none; overflow: hidden;" title="Dailymotion Video Player" frameborder="0" allow="web-share" allowfullscreen></iframe>';
            $content = str_replace($replace, $new, $content);
        }
    }

    // Vimeo
    $tag = $tags["vimeo_tag"];
    preg_match_all('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', $content, $videocode);
    if (isset($videocode['0']) && is_array($videocode['0'])) {
        for ($i=0; $i < count($videocode['0']); $i++) {
            $video =  $videocode['1'][$i];
            $replace = $videocode['0'][$i];
            $new = '<iframe src="https://player.vimeo.com/video/'.esc_html($video).'" style="width: 100%; height: auto; aspect-ratio: '.$aspect_ratio.'; border: none; overflow: hidden;" title="Vimeo Video Player" frameborder="0" allowfullscreen></iframe>';
            $content = str_replace($replace, $new, $content);
        }
    }

    // Quicktime
    $tag = $tags["quicktime_tag"];
    preg_match_all('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', $content, $videocode);
    if (isset($videocode['0']) && is_array($videocode['0'])) {
        for ($i=0; $i < count($videocode['0']); $i++) {
            $video =  $videocode['1'][$i];
            $replace = $videocode['0'][$i];
            $new = '<video style="width: 100%; height: auto; aspect-ratio: '.$aspect_ratio.'; border: none; overflow: hidden;" controls>
                <source src="'.esc_html($video).'" type="video/quicktime">
                Your browser does not support the video tag.
            </video>';
            $content = str_replace($replace, $new, $content);
        }
    }

    // Windows media player
    $tag = $tags["windowsmedia_tag"];
    preg_match_all('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', $content, $videocode);
    if (isset($videocode['0']) && is_array($videocode['0'])) {
        for ($i=0; $i < count($videocode['0']); $i++) {
            $video =  $videocode['1'][$i];
            $replace = $videocode['0'][$i];
            $new = '<video style="width: 100%; height: auto; aspect-ratio: '.$aspect_ratio.'; border: none; overflow: hidden;" controls>
                <source src="'.esc_html($video).'" type="video/x-ms-wmv">
                <source src="'.esc_html($video).'" type="video/wmv">
                Your browser does not support the video tag.
            </video>';
            $content = str_replace($replace, $new, $content);
        }
    }

    return $content;
}

function getDefaultOptionValue($optionName) {
    $defaults = [
        "video_width" => "16",
        "video_height" => "9",
        "youtube_tag" => "youtube",
        "dailymotion_tag" => "daily",
        "vimeo_tag" => "vimeo",
        "quicktime_tag" => "quicktime",
        "windowsmedia_tag" => "windowsmedia"
    ];

    return isset($defaults[$optionName]) ? $defaults[$optionName] : '';
}
?>