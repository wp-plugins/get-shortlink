<?php

require_once("./rich-related-config.php");
$wpconfig = cc_get_wpconfig();
require($wpconfig);
global $rrp;

$request = $_REQUEST;
$action = $request["action"];

require_once(ABSPATH.WPINC."/pluggable.php");
check_ajax_referer('rrp_nonce');

switch ($action) {
    case "quick":
        $rrp->get_quick_post($request["headline"]);
        break;
    case "get":
        $rrp->get_group($request["id"]);
        break;
    case "delete":
        $rrp->delete_group($request["id"]);
        break;
    case "save":
    case "saveas":
        $request = json_decode($request["req"]);
        $rrp->save_group($request);
        break;
    case "getlength":
        $rrp->get_groups_length();
        break;
}

?>