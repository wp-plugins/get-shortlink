<?php

/*
Plugin Name: Get Shortlink Lite
Plugin URI: http://www.splicelicio.us/get-shortlink-wordpress-plugin
Description: Get Shortlink places a button in your theme which allows visitors to grab the short url for that page by copying the shortlink to their clipboard. Supports shortening services tinyurl, digg, is.gd, and also allows you to generate custom shortlinks based on your site's domain name. Brought to you by <a href="http://www.splicelicio.us" title="Splicelicio.us">Splicelicio.us</a> & <a href="http://www.susty.com" title="Susty">Susty.com</a>.
Version: 1.1.9
Author: George Spyros
Author URI: http://www.bigcitypix.com/george-spyros-founder-creative-director-executive-producer-profile-bio

Copyright 2009 Big City Pictures, Inc. (email : sales@susty.com)
*/

require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/code/defaults.php");
require_once(dirname(__FILE__)."/code/functions.php");
require_once(dirname(__FILE__)."/code/database.php");
require_once(dirname(__FILE__)."/code/services.php");
require_once(dirname(__FILE__)."/gdragon/gd_debug.php");
require_once(dirname(__FILE__)."/gdragon/gd_functions.php");

if (!class_exists('GetShortcode')) {
    class GetShortcode {
        var $plugin_url;
        var $plugin_path;
        var $admin_plugin;
        var $script;
        var $form;
        var $o;
        var $l;

        var $default_options;

        function GetShortcode() {
            $gdd = new GESHDefaults();
            $this->default_options = $gdd->default_options;
            define('GETSHORTCODE_INSTALLED', $this->default_options["version"]." ".$this->default_options["status"]);

            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
        }

        function get($setting) {
            return $this->o[$setting];
        }

        function install_plugin() {
            $this->o = get_option('get-shortlink');

            if (!is_array($this->o)) {
                update_option('get-shortlink', $this->default_options);
                $this->o = get_option('get-shortlink');
            }
            else {
                $this->o = gdFunctionsGESH::upgrade_settings($this->o, $this->default_options);

                $this->o["version"] = $this->default_options["version"];
                $this->o["date"] = $this->default_options["date"];
                $this->o["status"] = $this->default_options["status"];
                $this->o["build"] = $this->default_options["build"];

                update_option('get-shortlink', $this->o);
            }

            $this->script = $_SERVER["PHP_SELF"];
            $this->script = end(explode("/", $this->script));
        }

        function plugin_path_url() {
            $this->plugin_url = WP_PLUGIN_URL.'/get-shortlink/';
            $this->plugin_path = dirname(__FILE__)."/";

            define('GETSHORTLINK_URL', $this->plugin_url);
            define('GETSHORTLINK_PATH', $this->plugin_path);
        }

        function actions_filters() {
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('admin_head', array(&$this, 'admin_head'));
            add_filter('the_content', array(&$this, 'the_content'));

            add_filter('query_vars', array(&$this, 'wp_rewrite_variables'));
            add_action('generate_rewrite_rules', array(&$this, 'wp_rewrite_rules'));
            add_action('parse_request', array(&$this, 'wp_parse_request'));

            add_shortcode(strtolower("getshortlink"), array(&$this, "shortcode_getshortlink"));
            add_shortcode(strtoupper("getshortlink"), array(&$this, "shortcode_getshortlink"));
        }

        function shortcode_getshortlink($atts = array()) {
            return $this->render_shorten();
        }

        function wp_rewrite_variables($qvars) {
            $qvars[] = "geshid";
            return $qvars;
        }

        function wp_rewrite_rules($wp_rewrite) {
            $rules = array();
            $rules[$this->o["short_prefix"].'([0-9]{1,})$'] = 'index.php?geshid='.$wp_rewrite->preg_index(1);
            $wp_rewrite->rules = $rules + $wp_rewrite->rules;
            return $wp_rewrite;
        }

        function wp_parse_request($obj) {
            $post_id = $obj->query_vars["geshid"];
            if ($post_id > 0) {
                $location = get_permalink($post_id);
                wp_redirect($location);
                exit;
            }
        }

        function the_content($content) {
            if (is_admin()) return $content;
            if (!is_feed()) {
                if ((is_single() && $this->o["display_posts"] == 1) ||
                    (is_page() && $this->o["display_pages"] == 1) ||
                    (is_home() && $this->o["display_home"] == 1) ||
                    (is_archive() && $this->o["display_archive"] == 1) ||
                    (is_search() && $this->o["display_search"] == 1)
                ) {
                    if ($this->o["insert_location"] == "top") $content = $this->render_shorten().$content;
                    if ($this->o["insert_location"] == "bottom") $content = $content.$this->render_shorten();
                }
            }
            return $content;
        }

        function render_shorten() {
            global $post;
            $url = $this->get_shortlink($post->ID);
            $form = $this->form;
            $form = str_replace('%ID%', $post->ID, $form);
            $form = str_replace('%TEXT%', $this->o["design_text"], $form);
            $form = str_replace('%URL%', $url, $form);
            return $form;
        }

        function get_shortlink($post_id) {
            if ($this->o["shortening_service"] == "friendly") {
                return trailingslashit(get_option("siteurl")).$this->o["short_prefix"].$post_id;
            } else {
                $obj = get_post_meta($post_id, "_gesh_".$this->o["shortening_service"], true);
                if ($obj != "") {
                    $obj = unserialize($obj);
                    if ($obj->is_changed()) {
                        $obj->shortlink = $this->create_shortlink($post_id, $this->o["shortening_service"]);
                        if (substr($obj->shortlink, 0, 4) != "http")
                            $obj->shortlink = trailingslashit(get_option("siteurl")).$this->o["short_prefix"].$post_id;
                        update_post_meta($post_id, "_gesh_".$this->o["shortening_service"], serialize($obj));
                    }
                    return $obj->shortlink;
                } else {
                    $obj = new geshPost($post_id);
                    $obj->shortlink = $this->create_shortlink($post_id, $this->o["shortening_service"]);
                    if (substr($obj->shortlink, 0, 4) != "http")
                        $obj->shortlink = trailingslashit(get_option("siteurl")).$this->o["short_prefix"].$post_id;
                    update_post_meta($post_id, "_gesh_".$this->o["shortening_service"], serialize($obj));
                    return $obj->shortlink;
                }
            }
        }

        function create_shortlink($post_id, $service) {
            $obj = null;
            switch ($service) {
                case "isgd":
                    $obj = new gsIsgd();
                    break;
                case "tinyurl":
                    $obj = new gsTinyurl();
                    break;
                case "digg":
                    $obj = new gsDigg(get_option("siteurl"));
                    break;
            }
            return $obj->get(get_permalink($post_id));
        }

        function init() {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
            $this->form = file_get_contents(GETSHORTLINK_PATH."render/form.html");

            wp_enqueue_script('jquery');

            if (!is_admin()) {
                wp_enqueue_script("gesh_script", plugins_url("get-shortlink/render/script.js"), array(), $this->o["version"]);
                wp_enqueue_style("gesh_style", plugins_url("get-shortlink/render/style.css"), array(), $this->o["version"]);
            }
        }

        function settings_operations() {
            if (isset($_POST['gesh_saving'])) {
                $this->o["display_home"] = isset($_POST['display_home']) ? 1 : 0;
                $this->o["display_posts"] = isset($_POST['display_posts']) ? 1 : 0;
                $this->o["display_pages"] = isset($_POST['display_pages']) ? 1 : 0;
                $this->o["display_archive"] = isset($_POST['display_archive']) ? 1 : 0;
                $this->o["display_search"] = isset($_POST['display_search']) ? 1 : 0;
                $this->o["shortening_service"] = $_POST['shortening_service'];
                $this->o["insert_location"] = $_POST['insert_location'];
                $this->o["design_text"] = $_POST['design_text'];
                $this->o["short_prefix"] = $_POST['short_prefix'];

                update_option("get-shortlink", $this->o);
                wp_redirect(add_query_arg("settings", "saved"));
                exit();
            }
        }

        function admin_init() {
            if (isset($_GET["page"])) {
                if ($_GET["page"] == "get-shortlink") {
                    $this->admin_plugin = true;
                }
            }
            $this->settings_operations();
        }

        function admin_menu() {
            add_options_page('Get Shortlink', 'Get Shortlink', 10, 'get-shortlink', array(&$this, "admin_front"));
        }

        function admin_head() {
            if ($this->admin_plugin) {
                wp_admin_css('css/dashboard');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin_main.css" type="text/css" media="screen" />');
            }
        }

        function admin_front() {
            $options = $this->o;
            include($this->plugin_path.'admin/settings.php');
        }
    }

    $gesh_debug = new gdDebugGESH(GETSHORTLINK_LOG_PATH);
    $gesh = new GetShortcode();

    function wp_show_getshortlink() {
        global $gesh;
        echo $gesh->render_shorten();
    }

    function wp_gesh_dump($msg, $obj, $block = "none", $mode = "a+") {
        if (GETSHORTLINK_DEBUG_ACTIVE) {
            global $gesh_debug;
            $gesh_debug->dump($msg, $obj, $block, $mode);
        }
    }
}
