<?php
/*
Plugin Name: Pikk-Digg widget plugin
Plugin Script: pikk-digg-plugin.php
Plugin URI: http://www.pikk.com/widgets/wordpress
Description: Displays Pikk and Digg badges and large Pikk Poll widget
Version: 1.13
License: GPL
Author: Pikk
Author URI: http://www.pikk.com

=== RELEASE NOTES ===
2009-03-05 - v1.0 - first version
2009-08-28 - v1.1 - Added poll button
2009-09-20 - v1.11 - Prevented buttons from showing on search pages.
2009-11-08 - v1.12 - Prevented buttons from showing on tag pages.
2009-11-13 - v1.13 - Prevented options from showing up in Write post page.
*/

/**
* Guess the wp-content and plugin urls/paths
*/
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

if (!defined('PLUGIN_URL'))
    define('PLUGIN_URL', WP_CONTENT_URL . '/plugins/');
if (!defined('PLUGIN_PATH'))
    define('PLUGIN_PATH', WP_CONTENT_DIR . '/plugins/');

function smow_metabox_module() {
    global $wpdb, $post_meta_cache;

    if(is_numeric($_GET['post'])) {
    	$post_ID = (int)$_GET['post'];

    	$pikk = get_post_meta($post_ID,'_smow_pikk');
    	$digg = get_post_meta($post_ID,'_smow_digg');
    	$large_pikk = get_post_meta($post_ID,'_smow_large_pikk');
    	$poll_pikk = get_post_meta($post_ID,'_smow_poll_pikk');

        $pikk_status = ($pikk[0] == 1)? "checked='checked'" : "";
        $digg_status = ($digg[0] == 1)? "checked='checked'" : "";
        $large_pikk_status = ($large_pikk[0] == 1)? "checked='checked'" : "";
        $poll_pikk_status = ($poll_pikk[0] == 1)? "checked='checked'" : "";

    } else {
        $pikk_status = "";
        $digg_status = "";
        $large_pikk_status = "";
        $poll_pikk_status = "";
    }
?>
		<div id="postvisibility" class="postbox">
				<h3> <?php _e('Select your widgets')?> </h3>
		<div class="inside">
		<div id="postvisibility">
<?php
                echo "<label class='selectit' for='smow_pikk'>
				<input id='smow_pikk' type='checkbox' $pikk_status value='1' name='smow_pikk' /> ";

                _e("Pikk badge (vote count and submit)");

                echo "
				</label><br />
                <lable class = 'selectit' for = 'smow_digg'>
				<input id='smow_digg' type='checkbox' $digg_status value='1' name='smow_digg' /> ";

                _e("Digg badge (vote count and submit)");

                echo "
				</label><br />
                <lable class = 'selectit' for = 'smow_large_pikk'>
				<input id='smow_large_pikk' type='checkbox' $large_pikk_status value='1' name='smow_large_pikk' /> " ;

                _e("Large Pikk widget (shows full headline and vote choices)");

                echo "</label> <br />";

                echo "
                <lable class = 'selectit' for = 'smow_poll_pikk'>
				<input id='smow_poll_pikk' type='checkbox' $poll_pikk_status value='1' name='smow_poll_pikk' /> " ;

                _e("Poll Pikk widget");
?>
		</div></div></div>
<?php
}


function smow_metabox_module_submit($post_ID) {

    if(is_numeric($post_ID) && ($_POST['smow_pikk'] == 1)) {
        update_post_meta($post_ID, "_smow_pikk", 1);
    } else {
        update_post_meta($post_ID, "_smow_pikk", 0);
    }

    if(is_numeric($post_ID) && ($_POST['smow_digg'] == 1)) {
        update_post_meta($post_ID, "_smow_digg", 1);
    } else {
        update_post_meta($post_ID, "_smow_digg", 0);
    }

    if(is_numeric($post_ID) && ($_POST['smow_large_pikk'] == 1)) {
        update_post_meta($post_ID, "_smow_large_pikk", 1);
    } else {
        update_post_meta($post_ID, "_smow_large_pikk", 0);
    }

    if(is_numeric($post_ID) && ($_POST['smow_poll_pikk'] == 1)) {
        update_post_meta($post_ID, "_smow_poll_pikk", 1);
    } else {
        update_post_meta($post_ID, "_smow_poll_pikk", 0);
    }

}

function smow_add_widgets($content) {
    global $post;

    if (!is_search() && !is_tag()) {
        $widgets = "";

        $pikk = get_post_meta($post->ID,'_smow_pikk');
        $digg = get_post_meta($post->ID,'_smow_digg');
        $large_pikk = get_post_meta($post->ID,'_smow_large_pikk');
        $poll_pikk = get_post_meta($post->ID,'_smow_poll_pikk');

        $post_permalink = get_permalink($post->ID);

        if ($pikk[0] == 1) {

            $widget_text = <<<CODE
<span style="margin: 0px 6px 0px 0px; float: right;">
<script>
pikk_skin = 'badge';
pikk_url='$post_permalink';
</script>
<script src="http://www.pikk.com/javascripts/widget.js" type="text/javascript"></script>
</span>
CODE;

            $widgets = $widgets . $widget_text;
        }

        if ($digg[0] == 1) {
            $widget_text = <<<CODE
<span style="margin: 0px 6px 0px 0px; float: right;">
<script type="text/javascript">
digg_url = '$post_permalink';
</script>
<script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>
</span>
CODE;

            $widgets = $widgets . $widget_text;
        }

        if ($large_pikk[0] == 1) {
            $widget_text = <<<CODE
<span style="margin: 0px 6px 0px 0px; float: right;">
<script>
pikk_skin = 'large';
pikk_url='$post_permalink';
</script>
<script src="http://www.pikk.com/javascripts/widget.js" type="text/javascript"></script>
</span>
CODE;

            $widgets = $widgets . $widget_text;

        }

        if ($poll_pikk[0] == 1) {
            $widget_text = <<<CODE
<span style="margin: 0px 6px 0px 0px; float: right;">
<script>
pikk_skin = 'poll';
pikk_url='$post_permalink';
</script>
<script src="http://www.pikk.com/javascripts/widget.js" type="text/javascript"></script>
</span>
CODE;
            $widgets = $widgets . $widget_text;
        }

        return $widgets . $content;
    } else {
        return $content;
    }
}

add_action('edit_form_advanced','smow_metabox_module');
add_action('edit_page_form', 'smow_metabox_module');

add_action('edit_post', 'smow_metabox_module_submit');
add_action('publish_post', 'smow_metabox_module_submit');
add_action('save_post', 'smow_metabox_module_submit');
add_action('edit_page_form', 'smow_metabox_module_submit');

add_filter('the_content', 'smow_add_widgets');
?>
