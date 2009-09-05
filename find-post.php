<?php

    require_once("./rich-related-config.php");
    $wpconfig = cc_get_wpconfig();
    require($wpconfig);
    global $rrp;

    $id = $_GET["id"];
    $mode = $_GET["mode"];
    
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        body { font-family: sans-serif; }
        h3 { padding: 5px 0 2px 0; margin: 0; font-size: 15px; }
        p { padding: 0; margin: 0; font-size: 11px; }
    </style>
    <script type='text/javascript' src="<?php echo get_option('home')."/".WPINC."/js/jquery/"; ?>jquery.js"></script>
    <script type='text/javascript'>
        var headline = "#rrpheadline-<?php echo $id; ?>";
        var element_id = "#rrppostid-<?php echo $id; ?>";
        var element_url = "#rrpposturl-<?php echo $id; ?>";
        var image_url = "#rrpimageurl-<?php echo $id; ?>";
        var mode = "<?php echo $mode; ?>";
        //<?php echo $rrp->o["thumb_field"]; ?>//

        function insert_back(id, url) {
            parent.jQuery(element_id).val(id);
            parent.jQuery(element_url).val(url);
            self.parent.tb_remove();
        }

        function insert_back_full(id, url, thumb) {
            parent.jQuery(element_id).val(id);
            parent.jQuery(element_url).val(url);
            parent.jQuery(image_url).val(thumb);
            self.parent.tb_remove();
        }

        function auto_submit() {
            var keyword = (mode == 'both' ? parent.jQuery(headline).val() : parent.jQuery(element_url).val());
            if (keyword != '' && keyword.substring(0, 7) != 'http://') {
                jQuery("#visited").val("yes");
                jQuery("#searchbox").val(keyword);
                jQuery("#searchform").submit();
            }
        }
    </script>
  </head>
  <body>

<form method="POST" action="" id="searchform">
    <input type="hidden" name="control" value="back" />
    Search Posts: <input id="searchbox" type="text" name="s" value="<?php echo $_POST["s"]; ?>" /><input id="searchbutton" name="search" type="submit" value="Search" />
</form>

<?php

    if ($_POST["control"] == "back") {
        echo '<h1>Search Results</h1>';
        $posts = get_posts(array('numberposts' => $rrp->o["limit_results"], "s" => $_POST["s"]));
        foreach ($posts as $post) {

?>

<h3>[<?php echo $post->ID; ?>] <a href="javascript:insert_back<?php
if ($mode == "both") echo "_full(".$post->ID.", '".get_permalink($post->ID)."', '".get_post_meta($post->ID, $rrp->o["thumb_field"], true)."'";
else echo "(".$post->ID.", '".get_permalink($post->ID)."'";
?>)"><?php echo $post->post_title; ?></a></h3>
<p>
    Author: <strong><?php echo get_userdata($post->post_author)->user_nicename; ?></strong><br />
    Published: <strong><?php echo $post->post_date; ?></strong>
</p>

<?php

        }
    }

?>

<?php if ($_POST["control"] != "back") echo '<script type="text/javascript">jQuery(document).ready(function() { auto_submit(); });</script>'; ?>
  </body>
</html>
