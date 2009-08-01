<?php

class geshPost {
    var $post_id = 0;
    var $permalink = "";
    var $shortlink = "";

    function geshPost($post_id) {
        $this->post_id = $post_id;
        $this->permalink = get_permalink($post_id);
    }

    function is_changed() {
        return $this->permalink != get_permalink($post_id);
    }
}

class geshService {
    var $name;
    var $home;

    function geshService() { }
    function get($url) { }
}

class gsIsgd extends geshService {
    var $name = "Is.gd";
    var $home = "http://is.gd/";

    function gsIsgd() { }

    function get($url) {
        $result = wp_remote_fopen("http://is.gd/api.php?longurl=".urlencode($url));
        return $result;
    }
}

class gsDigg extends geshService {
    var $name = "Digg";
    var $home = "http://digg.com/";
    var $akey = "";

    function gsDigg($appkey) {
        $this->akey = $appkey;
    }

    function get($url) {
        $result = wp_remote_fopen(sprintf("http://services.digg.com/url/short/create?url=%s&appkey=%s&type=json",
                urlencode($url),
                urlencode($this->akey)
            ));
        $decode = json_decode($result);
        return $decode->shorturls[0]->short_url;
    }
}

class gsTinyurl extends geshService {
    var $name = "Tinyurl";
    var $home = "http://tinyurl.com/";

    function gsTinyurl() { }

    function get($url) {
        $result = wp_remote_fopen("http://tinyurl.com/api-create.php?url=".urlencode($url));
        return $result;
    }
}

?>
