<?php

/*
Plugin Name: Rich Related Posts
Plugin URI: http://www.splicelicio.us/rich-related-posts-wordpress-plugin
Description: Powerful CMS solution to quickly and easily create multiple groups of rich related content with images using tools such as combination search/entry fields and element units consisting of custom headline + permalink + image url.  Brought to you by <a href="http://www.splicelicio.us" title="Splicelicio.us">Splicelicio.us</a> & <a href="http://www.susty.com" title="Susty">Susty.com</a>.
Version: 1.0.0
Author: George Spyros
Author URI: http://www.bigcitypix.com/george-spyros-founder-creative-director-executive-producer-profile-bio

Copyright 2009 Big City Pictures, Inc. (email : sales@susty.com)

*/

define('RICHRELATED_DEBUG_PATH', dirname(__FILE__).'/debug.txt');

require_once(ABSPATH."/wp-admin/includes/image.php");

require_once(dirname(__FILE__)."/code/rich_classes.php");
require_once(dirname(__FILE__)."/code/rich_functions.php");
require_once(dirname(__FILE__)."/gdragon/gd_debug.php");
require_once(dirname(__FILE__)."/gdragon/gd_functions.php");

if (!class_exists('RichRelatedPosts')) {
    class RichRelatedPosts {
        var $plugin_url;
        var $plugin_path;
        var $ajax;

        var $o;
        var $g;
        var $t;

        var $default_options = array(
            'version' => '1.0.5',
            'build' => 19,
            'limit_results' => 20,
            'panel_size' => 485,
            'thumb_field' => 'thumbnail'
        );

        var $default_shortcode_richrelatedpost = array(
            "id" => 1
        );

        function RichRelatedPosts() {
            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
        }

        function upgrade_settings($old, $new) {
            foreach ($new as $key => $value) {
                if (!isset($old[$key])) $old[$key] = $value;
            }

            $unset = Array();
            foreach ($old as $key => $value) {
                if (!isset($new[$key])) $unset[] = $key;
            }

            foreach ($unset as $key) {
                unset($old[$key]);
            }

            return $old;
        }

        function plugin_path_url() {
            $this->plugin_url = WP_PLUGIN_URL.'/rich-related-posts/';
            $this->plugin_path = dirname(__FILE__)."/";
            $this->ajax = $this->plugin_url."ajax.php";

            define('RICHRELATED_URL', $this->plugin_url);
            define('RICHRELATED_PATH', $this->plugin_path);
            define('RICHRELATED_AJAX', $this->ajax);
        }

        function install_plugin() {
            $this->o = get_option('rich-related-posts-settings');
            $this->g = get_option('rich-related-posts-groups');
            $this->t = get_option('rich-related-posts-templates');

            if (!is_array($this->o)) {
                $this->o = $this->default_options;
                update_option('rich-related-posts-settings', $this->o);
            }
            else {
                $this->o = $this->upgrade_settings($this->o, $this->default_options);
                update_option('rich-related-posts-settings', $this->o);
            }

            if (!is_object($this->g)) {
                $this->g = new RichRelatedGroups();
                update_option('rich-related-posts-groups', $this->g);
            }

            if (!is_object($this->t)) {
                $this->t = new RichRelatedTemplates();
                update_option('rich-related-posts-templates', $this->t);
            }
        }

        function actions_filters() {
            add_action('init', array(&$this, 'wp_main_init'));
            add_action('wp_head', array(&$this, 'wp_blog_head'));
            add_action('admin_menu', array(&$this, 'wp_admin_menu'));
            add_action('admin_head', array(&$this, 'wp_admin_head'));

            add_shortcode(strtolower("richrelatedpost"), array(&$this, "shortcode_richrelatedpost"));
            add_shortcode(strtoupper("richrelatedpost"), array(&$this, "shortcode_richrelatedpost"));
        }

        function shortcode_richrelatedpost($atts = array()) {
            $atts = shortcode_atts($this->default_shortcode_richrelatedpost, $atts);
            $group = $this->g->get_group($atts["id"]);
            $template = $this->t->get_template($group->template);
            $result = html_entity_decode($template->before);
            $element = html_entity_decode($template->element);
            foreach ($group->elements as $el) {
                if ($el->thumb_id != 0 && $el->thumb_id != '' && wp_get_attachment_url($el->thumb_id) == $el->thumbnail) {
                    $thumb = wp_get_attachment_url($el->thumb_id);
                    $ext = end(explode(".", $thumb));
                    $thumb = substr($thumb, 0, strlen($thumb) - 1 - strlen($ext))."-".$group->thumb_x."x".$group->thumb_y.".".$ext;
                    if (!file_exists($thumb)) image_resize(get_attached_file($el->thumb_id), $group->thumb_x, $group->thumb_y, true);
                    $render = str_replace('{image}', $thumb, $element);
                }
                else $render = str_replace('{image}', $el->thumbnail, $element);
                $render = str_replace('{url}', $el->permalink, $render);
                $result.= str_replace('{headline}', $el->headline, $render);
            }
            $result.= html_entity_decode($template->after);
            return $result;
        }

        function wp_admin_menu() {
            add_menu_page('Rich Related', 'Rich Related', 10, __FILE__, array(&$this,"admin_page_manage"));
            add_submenu_page(__FILE__, 'Template', 'Template', 10, __FILE__, array(&$this,"admin_page_manage"));
            add_submenu_page(__FILE__, 'Settings', 'Settings', 10, "rrp-settings", array(&$this,"admin_page_settings"));
        }

        function wp_main_init() {
            global $wp_version;
            define(RICHRELATED_WPV, substr(str_replace('.', '', $wp_version), 0, 2));
            wp_enqueue_script('jquery');
        }

        function wp_admin_head() {
            add_meta_box("rrp-meta-box", "Rich Related Posts", array(&$this, 'editbox_post'), "post", "advanced", "high");
            add_meta_box("rrp-meta-box", "Rich Related Posts", array(&$this, 'editbox_post'), "page", "advanced", "high");
            echo("\r\n".'<script type="text/javascript">var rrp_url = "'.RICHRELATED_AJAX.'"; var rrp_gfx = "'.RICHRELATED_URL.'";</script>'."\r\n");
            echo('<script type="text/javascript" src="'.$this->plugin_url.'js/json.js"></script>'."\r\n");
            echo('<script type="text/javascript" src="'.$this->plugin_url.'js/select.js"></script>'."\r\n");
            echo('<script type="text/javascript" src="'.$this->plugin_url.'js/richrelated.js"></script>'."\r\n");
            echo('<script type="text/javascript">'."\r\n");
            echo('jQuery(document).ready(function() { '."\r\n");
            include($this->plugin_path.'admin/js.php');
            echo("\r\n".'});</script>'."\r\n");
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin.css" type="text/css" media="screen" />');
        }

        function wp_blog_head() {
        }

        function editbox_post() {
            $options = $this->o;
            $groups = $this->g;
            $tpl = $this->t;
            include($this->plugin_path.'admin/edit.php');
        }

        function admin_page_manage() {
            $options = $this->o;
            $groups = $this->g;
            $tpl = $this->t;
            include($this->plugin_path."admin/templates.php");
        }

        function admin_page_settings() {
            $options = $this->o;
            include($this->plugin_path."admin/settings.php");
        }

        function delete_group($id) {
            $this->g->delete_group($id);
            update_option('rich-related-posts-groups', $this->g);
            echo json_encode("Group deleted");
        }

        function get_quick_post($headline) {
            echo json_encode(get_closest_post($headline, $this->o["thumb_field"]));
        }

        function get_group($id) {
            echo json_encode($this->g->get_group($id));
        }

        function save_group($request) {
            if (($request->action == "saveas") || ($request->action == "save" && $request->id == 0)) {
                $gr = $this->g->add_group($request);
                update_option('rich-related-posts-groups', $this->g);
                echo json_encode($gr);
            }
            else if ($request->action == "save" && $request->id > 0) {
                $gr = $this->g->edit_group($request);
                update_option('rich-related-posts-groups', $this->g);
                echo json_encode($gr);
            }
            else echo json_encode("Invalid request");
        }
		
		function get_groups_length() {
			echo json_encode($this->g->get_groups_length());
		}
    }

    $rrp_debug = new gdDebug(RICHRELATED_DEBUG_PATH);
    $rrp = new RichRelatedPosts();

    function wp_rrp_dump($msg, $obj, $block = "none", $mode = "a+") {
    	global $rrp_debug;
        $rrp_debug->dump($msg, $obj, $block, $mode);
    }
}